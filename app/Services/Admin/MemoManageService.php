<?php
namespace App\Services\Admin;

use App\Enums\MemoAdminReviewStatusType;
use App\Enums\MemoApprovedByType;
use App\Enums\MemoChatGptReviewStatusType;
use App\Enums\MemoStatusType;
use App\Http\Resources\Admin\MemoManageResource;
use App\Repositories\Admin\MemoManageRepository;
use App\Mail\MemoFixRequest;
use App\Services\NotifyToUserService;
use App\Traits\ServiceInstanceTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MemoManageService
{
    use ServiceInstanceTrait;

    private MemoManageRepository $repository;

    /**
     * コンストラクタ
     *
     * @param MemoManageRepository|null $repository
     */
    public function __construct(MemoManageRepository $repository = null)
    {
        $this->repository = $repository ?? app(MemoManageRepository::class);
    }

    /**
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function adminMemoList(): AnonymousResourceCollection
    {
        try {
            $memos = $this->repository->adminMemoList();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return MemoManageResource::collection($memos);
    }

    /**
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function adminMemoWaitingReviewList(): AnonymousResourceCollection
    {
        try {
            $memos = $this->repository->adminMemoWaitingReviewList();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return MemoManageResource::collection($memos);
    }

    public function adminMemoWaitingFixList(): AnonymousResourceCollection
    {
        try {
            $memos = $this->repository->adminMemoWaitingFixList();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return MemoManageResource::collection($memos);
    }

    /**
     * @param string $keyword
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function adminMemoSearch(string $keyword): AnonymousResourceCollection
    {
        try {
            $memos = $this->repository->adminMemoSearch($keyword);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return MemoManageResource::collection($memos);
    }

    /**
     * @param int $id
     * @return MemoManageResource
     */
    public function adminMemoShow(int $id): MemoManageResource
    {
        $memo = $this->repository->getMemoById($id);

        return new MemoManageResource($memo);
    }

    /**
     * 管理者による審査の結果、メモの内容が問題ないと判断された場合の処理
     *
     * @param int $id Memo ID
     * @return JsonResponse
     * @throws Exception
     */
    public function adminMemoApprove(int $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $memo = $this->repository->getMemoById($id);

            // Update the memo's status
            $memo->chatgpt_review_status = MemoChatGptReviewStatusType::VERIFIED_BY_ADMIN; // 管理者審査済
            $memo->admin_review_status = MemoAdminReviewStatusType::PASSED_ADMIN_REVIEW; // 管理者による審査通過
            $memo->admin_reviewed_at = now()->format('Y-m-d H:i:s'); // 管理者による審査日時
            // メモのステータスが「修正待ち」の場合は、変更前のステータスをセットする
            if ($memo->status === MemoStatusType::WAITING_FOR_FIX) {
                if ($memo->status_at_review) {
                    $memo->status = $memo->status_at_review;
                } else {
                    Log::warning("status_at_review is null or invalid for memo ID: {$id}");
                    throw new Exception("status_at_review is null or invalid for memo ID: {$id}");
                }
            }
            $memo->approved_by = MemoApprovedByType::BY_ADMIN; // 承認者に管理者をセット
            $memo->approved_at = now()->format('Y-m-d H:i:s'); // 承認日時
            $memo->timestamps = false; // updated_at が更新されないようにする
            $memo->save();

            DB::commit();

            return response()->json(['message' => '管理者による審査の結果、メモが承認されました。']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json(['error' => '管理者によるメモの承認リクエストに失敗しました。'], 500);
        }
    }

    /**
     * 管理者による審査の結果、メモの内容が不適切と判断された場合の、管理者によるメモの修正依頼（記事の掲載を一時停止にする）
     *
     * @param int $id Memo ID
     * @return JsonResponse
     */
    public function adminMemoSetWaitingForFix(int $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $memo = $this->repository->getMemoById($id);
            // メモのステータスが「修正待ち」以外の場合は、変更前のステータスを保持する
            if ($memo->status !== MemoStatusType::WAITING_FOR_FIX) {
                $lastMemoStatus = $memo->status;
            }
            $user = $memo->user;

            // Update the memo's status
            $memo->status = MemoStatusType::WAITING_FOR_FIX; // 修正待ち
            $memo->admin_review_status = MemoAdminReviewStatusType::FIX_REQUIRED; // 修正依頼中
            $memo->admin_reviewed_at = now()->format('Y-m-d H:i:s'); // 管理者による審査日時
            if (isset($lastMemoStatus)) {
                $memo->status_at_review = $lastMemoStatus;
            }
            $memo->times_notified_to_fix = $memo->times_notified_to_fix + 1; // 修正依頼通知回数をカウントアップ
            $memo->timestamps = false; // updated_at が更新されないようにする
            $memo->save();

            $user->increment('total_times_notified_to_fix'); // 総修正依頼通知回数をカウントアップ

            DB::commit();

            $notifyToAdminService = $this->getServiceInstance(NotifyToUserService::class);
            // 管理者がメモの修正必要と判断して、記事の掲載が一時停止になったことをユーザーに通知する
            $notifyToAdminService->notifyUserMemoFixRequestEmail($memo, $user);

            return response()->json(['message' => 'メモの修正リクエストを送信しました。']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json(['error' => 'メモの修正リクエストに失敗しました。'], 500);
        }
    }
    public function adminMemoDestroy(int $id): JsonResponse
    {
        $memo = $this->repository->getMemoById($id);
        if ($this->repository->adminMemoDestroy($memo)) {
            return response()->json(['message' => 'メモを強制的に削除しました。'], 200);
        } else {
            return response()->json(['error' => 'メモの強制削除に失敗しました。'], 500);
        }
    }

    /**
     * @param int $categoryId
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function adminMemoListByCategory(int $categoryId): AnonymousResourceCollection
    {
        try {
            $memos = $this->repository->adminMemoListByCategory($categoryId);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return MemoManageResource::collection($memos);
    }

    /**
     * @param string $tag
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function adminMemoListByTag(string $tag): AnonymousResourceCollection
    {
        try {
            $memos = $this->repository->adminMemoListByTag($tag);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return MemoManageResource::collection($memos);
    }

    /**
     * @param int $categoryId
     * @param string $tag
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function adminMemoListByCategoryAndTag(int $categoryId, string $tag): AnonymousResourceCollection
    {
        try {
            $memos = $this->repository->adminMemoListByCategoryAndTag($categoryId, $tag);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return MemoManageResource::collection($memos);
    }

    /**
     * @param string $nickname
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function adminNicknameMemoList(string $nickname): AnonymousResourceCollection
    {
        try {
            $memos = $this->repository->adminNicknameMemoList($nickname);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return MemoManageResource::collection($memos);
    }


    /**
     * @param string $nickname
     * @param int $categoryId
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function adminNicknameMemoListByCategory(string $nickname, int $categoryId): AnonymousResourceCollection
    {
        try {
            $memos = $this->repository->adminNicknameMemoListByCategory($nickname, $categoryId);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return MemoManageResource::collection($memos);
    }

    /**
     * @param string $nickname
     * @param string $tag
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function adminNicknameMemoListByTag(string $nickname, string $tag): AnonymousResourceCollection
    {
        try {
            $memos = $this->repository->adminNicknameMemoListByTag($nickname, $tag);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return MemoManageResource::collection($memos);
    }

    /**
     * @param string $nickname
     * @param int $categoryId
     * @param string $tag
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function adminNicknameMemoListByCategoryAndTag(string $nickname, int $categoryId, string $tag): AnonymousResourceCollection
    {
        try {
            $memos = $this->repository->adminNicknameMemoListByCategoryAndTag($nickname, $categoryId, $tag);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return MemoManageResource::collection($memos);
    }
}
