<?php /** @noinspection PhpPossiblePolymorphicInvocationInspection */

namespace App\Services;

use App\Http\Resources\MemoResource;
use App\Models\Memo;
use App\Repositories\DashboardMemoRepository;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class DashboardMemoService
{
    private DashboardMemoRepository $repository;

    /**
     * コンストラクタ
     *
     * @param DashboardMemoRepository|null $repository
     */
    public function __construct(DashboardMemoRepository $repository = null)
    {
        $this->repository = $repository ?? app(DashboardMemoRepository::class);
    }

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
        $memo = $this->repository->getMemoById($id);

        if ($user->cannot('dashboardMemoShow', $memo)) {
            throw new AuthorizationException('指定されたIDのメモを表示する権限がありません。');
        }
        return new MemoResource($memo);
    }


    /**
     * @param array $validated
     * @param Authenticatable $user
     * @return JsonResponse
     * @throws Exception
     */
    public function edit(array $validated, Authenticatable $user): JsonResponse
    {
        try {
            DB::beginTransaction();

            $memo = $this->repository->getMemoById($validated['id']);

            if ($user->cannot('update', $memo)) {
                throw new AuthorizationException('指定されたIDのメモを更新する権限がありません。');
            }
            // モデルの保存
            if (!$this->repository->updateMemo($memo, $validated)) {
                throw new Exception('メモの編集に失敗しました。');
            }

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
        $memo = $this->repository->getMemoById($id);

        if ($user->cannot('delete', $memo)) {
            throw new AuthorizationException('指定されたIDのメモを削除する権限がありません。');
        }
        $memo->delete();
        return response()->json(['message' => 'Memo deleted'], 200);
    }
}
