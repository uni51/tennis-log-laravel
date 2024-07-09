<?php

namespace App\Services;

use App\Enums\MemoStatusType;
use App\Lib\MemoHelper;
use App\Models\User;
use App\Traits\ServiceInstanceTrait;
use App\Http\Resources\MemoResource;
use App\Models\Memo;
use App\Repositories\DashboardMemoRepository;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class DashboardMemoService
{
    use ServiceInstanceTrait;

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
     * @param User $user
     * @param string $abilities
     * @return Memo
     * @throws AuthorizationException
     */
    private function validateUserPermission(int $memoId, User $user, string $abilities): Memo
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
     * @param User $user
     * @param array $validated
     * @return JsonResponse
     * @throws Exception
     */
    public function dashboardMemoCreate(array $validated, User $user): JsonResponse
    {
        // サービスインスタンスの取得
        $contentInspectionService = $this->getServiceInstance(ContentInspectionService::class);
        // ChatGPTによる内容に不適切な表現がないかのチェック
        $validateErrorResponse = $contentInspectionService->validateIsInappropriate($validated, $user);
        if ($validateErrorResponse) {
            return $validateErrorResponse;
        }

        // 「下書き」以外のステータスの場合、ChatGPTによるテニスに関連しない内容かどうかのチェックを行う
        $isNotTennisRelated = false;
        if ($validated['status'] !== MemoStatusType::DRAFT) {
            $isNotTennisRelated = $this->checkIsNotTennisRelated($validated);
            $validated = MemoHelper::setReviewValueByChatGpt($validated, $isNotTennisRelated);
        }

        $memo = $this->repository->dashboardMemoCreate($validated);

        // サービスインスタンスの取得
        $notifyToAdminService = $this->getServiceInstance(NotifyToAdminService::class);
        if ($isNotTennisRelated) {
            $actionType = 'create';
            // テニスに関連のないメモとChatGPTに判断された場合は、管理者にその旨をメール送信
            $notifyToAdminService->notifyAdminNotTennisRelatedEmail($memo, $user, $actionType);
        } else {
            // 「下書き」以外のステータスで、テニスに関連するメモとChatGPTに判断された場合は、管理者に新規投稿があったことのメール送信
            if ($memo->status !== MemoStatusType::DRAFT) {
                $notifyToAdminService->notifyAdminCreateMemoEmail($memo, $user);
            }
        }

        return response()->json([
            'message' => 'メモの登録に成功しました。'
        ], 201);
    }

    /**
     * @param int $id
     * @param User $user
     * @return MemoResource
     * @throws AuthorizationException
     */
    public function dashboardMemoShow(int $id, User $user): MemoResource
    {
        $memo = $this->validateUserPermission($id, $user, 'dashboardMemoShow');

        return new MemoResource($memo);
    }

    /**
     * @param array $validated
     * @param User $user
     * @return JsonResponse
     * @throws Exception
     * @throws AuthorizationException
     */
    public function dashboardMemoEdit(array $validated, User $user): JsonResponse
    {
        $memo = $this->validateUserPermission($validated['id'], $user, 'update');

        // サービスインスタンスの取得
        $contentInspectionService = $this->getServiceInstance(ContentInspectionService::class);
        // ChatGPTによる内容に不適切な表現がないかのチェック
        $validateErrorResponse = $contentInspectionService->validateIsInappropriate($validated, $user);
        if ($validateErrorResponse) {
            return $validateErrorResponse;
        }

        if ($memo->status === MemoStatusType::WAITING_FOR_FIX) {
            if (!$this->processMemoFixUpdate($validated, $memo, $user)) {
                throw new Exception('メモの編集に失敗しました。');
            }
        } else {
            if (!$this->processMemoUpdate($validated, $memo, $user)) {
                throw new Exception('メモの編集に失敗しました。');
            }
        }

        return response()->json([
            'message' => 'メモの編集に成功しました。'
        ], 201);
    }

    /**
     * @param array $validated
     * @param Memo $memo
     * @param User $user
     * @return bool
     */
    private function processMemoUpdate(array $validated, Memo $memo, User $user): bool
    {
        $isNotTennisRelated = false;
        // 「下書き」以外のステータスの場合、ChatGPTによるテニスに関連しない内容かどうかのチェックを行う
        if ($validated['status'] !== MemoStatusType::DRAFT) {
            $isNotTennisRelated = $this->checkIsNotTennisRelated($validated);
            $validated = MemoHelper::setReviewValueByChatGpt($validated, $isNotTennisRelated);
        }

        DB::beginTransaction();
        try {
            $memo = $this->repository->updateMemo($memo, $validated);
            $this->repository->syncTagsToMemo($memo, $validated['tags']);
            DB::commit();
            if ($isNotTennisRelated) {
                // サービスインスタンスの取得
                $notifyToAdminService = $this->getServiceInstance(NotifyToAdminService::class);
                // テニスに関連のないメモとChatGPTに判断された場合は、管理者にメール送信
                $actionType = 'edit';
                $notifyToAdminService->notifyAdminNotTennisRelatedEmail($memo, $user, $actionType);
            }
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    /**
     * @param array $validated
     * @param Memo $memo
     * @param User $user
     * @return bool
     */
    private function processMemoFixUpdate(array $validated, Memo $memo, User $user): bool
    {
        // ChatGPTによるテニスに関連しない内容かどうかのチェック
        $isNotTennisRelated = $this->checkIsNotTennisRelated($validated);
        $validated = MemoHelper::setFixReviewValueByChatGpt($validated, $isNotTennisRelated, $memo);

        DB::beginTransaction();
        try {
            $memo = $this->repository->updateMemo($memo, $validated);
            $this->repository->syncTagsToMemo($memo, $validated['tags']);
            $user->increment('total_times_attempt_to_fix'); // 修正を試みた総回数をカウントアップ
            DB::commit();
            // サービスインスタンスの取得
            $notifyToAdminService = $this->getServiceInstance(NotifyToAdminService::class);
            if ($isNotTennisRelated) {
                // テニスに関連のないメモとChatGPTに判断された場合は、管理者にメール送信
                $actionType = 'edit';
                $notifyToAdminService->notifyAdminNotTennisRelatedEmail($memo, $user, $actionType);
            } else {
                // テニスに関連するメモとChatGPTに判断された場合は、管理者に記事が修正された旨をメール送信
                $notifyToAdminService->notifyAdminFixMemoEmail($memo, $user);
            }
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    private function checkIsNotTennisRelated(array $validated): bool
    {
        return $this->openAIService->isNotTennisRelated(
            $validated['title'] . "\n" . $validated['body'] . "\n" . implode("\n", $validated['tags'])
        );
    }

    /**
     * @param int $id
     * @param User $user
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function dashboardMemoDestroy(int $id, User $user): JsonResponse
    {
        $memo = $this->validateUserPermission($id, $user, 'delete');

        DB::beginTransaction();
        try {
            // DeletedMemoテーブルにデータを移動
            $this->repository->archiveMemo($memo);
            // memo_tagの中間テーブルに関連付けられたタグをアーカイブして、関連を削除する。
            $this->repository->archiveAndDetachMemoTags($memo);
            // Tagテーブルから使用されていないタグを削除
            // $this->repository->archiveAndDeleteUserUnusedTags($user);
            // メモ自体を削除
            $memo->delete();
            DB::commit();
            return response()->json(['message' => 'Memo deleted'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json(['error' => 'Failed to delete memo'], 500);
        }
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
