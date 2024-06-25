<?php
namespace App\Services\Admin;

use App\Http\Resources\Admin\UserManageResource;
use App\Models\DeletedMemoTag;
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
                'count_inappropriate_posts' => $user->count_inappropriate_posts,
                'total_times_notified_to_fix'=> $user->total_times_notified_to_fix,
                'total_times_attempt_to_fix' => $user->total_times_attempt_to_fix,
                'total_times_delete_memos_by_admin' => $user->total_times_delete_memos_by_admin,
                'total_times_delete_tag_by_admin' => $user->total_times_delete_tag_by_admin,
                'remember_token' => $user->remember_token,
                'user_created_at' => $user->created_at,
                'user_updated_at' => $user->updated_at,
                'is_force_deleted' => true,
            ]);

            // メモのデータを移動
            $deleteTargetMemos = $user->memos()->get();
            foreach ($deleteTargetMemos as $deleteTargetMemo) {
                // メモとタグの関連をdelete_memo_tagに保存
                $memoTags = $deleteTargetMemo->tags()->get(); // 仮定しています。タグを取得する実際のメソッドに適宜置き換えてください。
                foreach ($memoTags as $tag) {
                    DeletedMemoTag::create([
                        'memo_id' => $deleteTargetMemo->id,
                        'tag_id' => $tag->id,
                        'memo_tag_created_at' => $deleteTargetMemo->created_at, // 必要に応じて調整してください
                        'memo_tag_updated_at' => $deleteTargetMemo->updated_at, // 必要に応じて調整してください
                        'is_force_deleted' => true,
                    ]);

                    // memo_tag中間テーブルから関連を削除
                    $deleteTargetMemo->tags()->detach($tag->id);
                }

                // 削除するメモのデータをDeletedMemoに移動
                DeletedMemo::create([
                    'memo_id' => $deleteTargetMemo->id,
                    'user_id' => $deleteTargetMemo->user_id,
                    'category_id' => $deleteTargetMemo->category_id,
                    'title' => $deleteTargetMemo->title,
                    'body' => $deleteTargetMemo->body,
                    'status' => $deleteTargetMemo->status,
                    'chatgpt_review_status' => $deleteTargetMemo->chatgpt_review_status,
                    'chatgpt_reviewed_at' => $deleteTargetMemo->chatgpt_reviewed_at,
                    'admin_review_status' => $deleteTargetMemo->admin_review_status,
                    'admin_reviewed_at' => $deleteTargetMemo->admin_reviewed_at,
                    'status_at_review' => $deleteTargetMemo->status_at_review,
                    'times_notified_to_fix' => $deleteTargetMemo->times_notified_to_fix,
                    'times_attempt_to_fix' => $deleteTargetMemo->times_attempt_to_fix,
                    'approved_by' => $deleteTargetMemo->approved_by,
                    'approved_at' => $deleteTargetMemo->approved_at,
                    'memo_created_at' => $deleteTargetMemo->created_at,
                    'memo_updated_at' => $deleteTargetMemo->updated_at,
                    'is_force_deleted' => true,
                ]);

                // メモを削除
                $deleteTargetMemo->delete();
            }

            // TODO: 不要なタグの削除処理を追加すること

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
