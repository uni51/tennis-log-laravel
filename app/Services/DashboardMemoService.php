<?php /** @noinspection PhpPossiblePolymorphicInvocationInspection */

namespace App\Services;

use App\Exceptions\MemoNotFoundException;
use App\Http\Resources\MemoResource;
use App\Models\Memo;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class DashboardMemoService
{
    /**
     * @param mixed $validated
     * @return JsonResponse
     * @throws Exception
     */
    public function create(mixed $validated): JsonResponse
    {
        try {
            DB::beginTransaction();

            // モデルクラスのインスタンス化
            $memo = new Memo();
            // パラメータのセット
            $memo->user_id = Auth::id();
            $memo->category_id = $validated['category_id'];
            $memo->status = $validated['status_id'];
            $memo->title = $validated['title'];
            $memo->body = $validated['body'];
            // モデルの保存
            $memo->save();

            // メモとタグの紐付け
            if (!empty($validated['tags'])) {
                $memo->retag($validated['tags']);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return response()->json([
            'message' => 'メモの登録に成功しました。'
        ], 201);
    }


    /**
     * @param int $id
     * @param Authenticatable $user
     * @return MemoResource
     * @throws AuthorizationException
     */
    public function show(int $id, Authenticatable $user): MemoResource
    {
        $memo = Memo::find($id);
        if (!$memo) {
            abort(404, '指定されたIDのメモが見つかりません。');
        }
        if ($user->cannot('dashboardMemoShow', $memo)) {
            throw new AuthorizationException('指定されたIDのメモを表示する権限がありません。');
        }
        return new MemoResource($memo);
    }


    /**
     * @param mixed $validated
     * @param Authenticatable $user
     * @return JsonResponse
     * @throws Exception
     */
    public function edit(mixed $validated, Authenticatable $user): JsonResponse
    {
        try {
            DB::beginTransaction();

            $memo = Memo::find($validated['id']);
            if (!$memo) {
                abort(404, '指定されたIDのメモが見つかりません。');
            }
            if ($user->cannot('update', $memo)) {
                throw new AuthorizationException('指定されたIDのメモを表示する権限がありません。');
            }
            // モデルの保存
            $memo->update([
                $memo->title = $validated['title'],
                $memo->body = $validated['body'],
                $memo->category_id = $validated['category_id'],
                $memo->status = $validated['status_id'],
            ]);

            // メモとタグの紐付け
            $memo->retag($validated['tags']);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return response()->json([
            'message' => 'メモの編集に成功しました。'
        ], 201);
    }

    /**
     * @param int $id
     * @param Authenticatable $user
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(int $id, Authenticatable $user): JsonResponse
    {
        $memo = Memo::find($id);
        if (!$memo) {
            abort(404, '指定されたIDのメモが見つかりません。');
        }
        if ($user->cannot('delete', $memo)) {
            throw new AuthorizationException('指定されたIDのメモを表示する権限がありません。');
        }
        $memo->delete();
        return response()->json(['message' => 'Memo deleted'], 200);
    }
}
