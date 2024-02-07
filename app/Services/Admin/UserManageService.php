<?php
namespace App\Services\Admin;

use App\Http\Resources\Admin\UserManageResource;
use App\Models\DeletedUser;
use App\Models\DeletedMemo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class UserManageService
{
    /**
     * @return AnonymousResourceCollection
     */
    public function list(): AnonymousResourceCollection
    {
        $users = User::orderBy('id', 'desc')->paginate(10);
        return UserManageResource::collection($users);
    }

    public function disable(Request $request)
    {
        DB::beginTransaction();
        try {
            $firebaseFactory = app()->make('firebase');
            $firebaseAuth = $firebaseFactory->createAuth();
            $user = User::find($request->userId);
            $updatedUser = $firebaseAuth->disableUser($user->firebase_uid);

            // DeletedUser モデルにユーザーデータを移動
            DeletedUser::create([
                'user_id' => $user->id,
                'firebase_uid' => $user->firebase_uid,
                'name' => $user->name,
                'nickname' => $user->nickname,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'password' => $user->password,
                'remember_token' => $user->remember_token,
                'force_deleted' => true,
            ]);

            // メモのデータを移動
            $deleteTargetMemos = $user->memos()->get();
            foreach ($deleteTargetMemos as $deleteTargetMemo) {
                DeletedMemo::create([
                    'user_id' => $deleteTargetMemo->user_id,
                    'category_id' => $deleteTargetMemo->category_id,
                    'title' => $deleteTargetMemo->title,
                    'body' => $deleteTargetMemo->body,
                    'status' => $deleteTargetMemo->status,
                    'force_deleted' => true,
                ]);

                // メモを削除
                $deleteTargetMemo->delete();
            }

            // ユーザーを削除
            $user->delete();

            DB::commit();
            return $updatedUser->disabled;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
