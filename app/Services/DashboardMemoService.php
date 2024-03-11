<?php

namespace App\Services;

use App\Http\Resources\MemoResource;
use App\Models\Memo;
use App\Repositories\DashboardMemoRepository;
use App\Services\OpenAIService;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class DashboardMemoService
{
    private DashboardMemoRepository $repository;
    protected OpenAIService $openAIService;

    /**
     * コンストラクタ
     *
     * @param DashboardMemoRepository|null $repository
     */
    public function __construct(OpenAIService $openAIService, DashboardMemoRepository $repository = null)
    {
        $this->repository = $repository ?? app(DashboardMemoRepository::class);
        $this->openAIService = $openAIService;
    }

    /**
     * @param int $memoId
     * @param Authenticatable $user
     * @param string $abilities
     * @return Memo
     * @throws AuthorizationException
     */
    private function validateUserPermission(int $memoId, Authenticatable $user, string $abilities): Memo
    {
        $memo = $this->repository->getMemoById($memoId);
        if ($user->cannot($abilities, $memo)) {
            throw match ($abilities) {
                'dashboardMemoShow' => new AuthorizationException('指定されたIDのメモを表示する権限がありません。'),
                'update' => new AuthorizationException('指定されたIDのメモを更新する権限がありません。'),
                'delete' => new AuthorizationException('指定されたIDのメモを削除する権限がありません。'),
                default => new AuthorizationException('指定されたIDのメモを編集する権限がありません。'),
            };
        }
        return $memo;
    }


    /**
     * @param array $validated
     * @return JsonResponse
     * @throws Exception
     */
    public function dashboardMemoCreate(array $validated): JsonResponse
    {
        // $result = $this->openAIService->isNotTennisRelated($validated['body']);

        $this->repository->dashboardMemoCreate($validated);

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
    public function dashboardMemoShow(int $id, Authenticatable $user): MemoResource
    {
        $memo = $this->validateUserPermission($id, $user, 'dashboardMemoShow');

        return new MemoResource($memo);
    }

    /**
     * @param array $validated
     * @param Authenticatable $user
     * @return JsonResponse
     * @throws Exception
     * @throws AuthorizationException
     */
    public function dashboardMemoEdit(array $validated, Authenticatable $user): JsonResponse
    {
        $memo = $this->validateUserPermission($validated['id'], $user, 'update');

        // タイトルに不適切な表現（差別的、暴力的、性的な表現や誹謗中傷）が含まれているか
        $isInappropriateTitle = $this->openAIService->checkForInappropriateContent($validated['title']);
        if ($isInappropriateTitle) {
            return response()->json([
                'errors' => [
                    'title' => ['不適切な表現が含まれています。修正してください。'],
                ],
            ], 422);
        }

        // 文章に不適切な表現（差別的、暴力的、性的な表現や誹謗中傷）が含まれているか
        $isInappropriateBody = $this->openAIService->checkForInappropriateContent($validated['body']);

        if ($isInappropriateBody) {
            return response()->json([
                'errors' => [
                    'original-message' => ['不適切な表現が含まれています。修正してください。'],
                ],
            ], 422);
        }

        $isNotTennisRelated = $this->openAIService->isNotTennisRelated($validated['body']);

        if ($isNotTennisRelated) {
            return response()->json([
                'errors' => [
                    'original-message' => ['テニスに関する内容ではありません。修正してください。'],
                ],
            ], 422);
        }

        if (!$this->processMemoUpdate($validated, $memo)) {
            throw new Exception('メモの編集に失敗しました。');
        }

        return response()->json([
            'message' => 'メモの編集に成功しました。'
        ], 201);
    }

    /**
     * @param array $validated
     * @param Memo $memo
     * @return bool
     */
    private function processMemoUpdate(array $validated, Memo $memo): bool
    {
        try {
            DB::beginTransaction();
            $this->repository->updateMemo($memo, $validated);
            $this->repository->syncTagsToMemo($memo, $validated['tags']);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    /**
     * @param int $id
     * @param Authenticatable $user
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function dashboardMemoDestroy(int $id, Authenticatable $user): JsonResponse
    {
        $memo = $this->validateUserPermission($id, $user, 'delete');
        $memo->tags()->detach(); // 中間テーブルのレコードを削除
        $memo->delete();
        $this->repository->deleteUnusedTags();
        return response()->json(['message' => 'Memo deleted'], 200);
    }

    /**
     * @param int $authUserId
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function dashboardMemoListByAuthUser(int $authUserId): AnonymousResourceCollection
    {
        try {
            $memos = $this->repository->dashboardMemoListByAuthUser($authUserId);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return MemoResource::collection($memos);
    }

    /**
     * @param int $authUserId
     * @param string $keyword
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function dashboardMemoSearch(int $authUserId, string $keyword): AnonymousResourceCollection
    {
        try {
            $memos = $this->repository->dashboardMemoSearch($authUserId, $keyword);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return MemoResource::collection($memos);
    }

    /**
     * @param int $authUserId
     * @param int $categoryId
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function dashboardMemoListByCategory(int $authUserId, int $categoryId): AnonymousResourceCollection
    {
        try {
            $memos = $this->repository->dashboardMemoListByCategory($authUserId, $categoryId);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return MemoResource::collection($memos);
    }

    /**
     * @param int $authUserId
     * @param int $status
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function dashboardMemoListByStatus(int $authUserId, int $status): AnonymousResourceCollection
    {
        try {
            $memos = $this->repository->dashboardMemoListByStatus($authUserId, $status);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return MemoResource::collection($memos);
    }

    /**
     * @param int $authUserId
     * @param string $tag
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function dashboardMemoListByTag(int $authUserId, string $tag): AnonymousResourceCollection
    {
        try {
            $memos = $this->repository->dashboardMemoListByTag($authUserId, $tag);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return MemoResource::collection($memos);
    }

    /**
     * @param int $authUserId
     * @param int $categoryId
     * @param string $tag
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function memoListByCategoryAndTag(int $authUserId, int $categoryId, string $tag): AnonymousResourceCollection
    {
        try {
            $memos = $this->repository->memoListByCategoryAndTag($authUserId, $categoryId, $tag);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return MemoResource::collection($memos);
    }
}
