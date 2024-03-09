<?php
namespace App\Services\Admin;

use App\Consts\MemoConst;
use App\Http\Resources\Admin\MemoManageResource;
use App\Http\Resources\MemoResource;
use App\Repositories\Admin\MemoManageRepository;
use App\Mail\MemoEditRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MemoManageService
{
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

        return MemoResource::collection($memos);
    }

    /**
     * @param int $id
     * @return MemoResource
     */
    public function adminMemoShow(int $id): MemoResource
    {
        $memo = $this->repository->getMemoById($id);

        return new MemoResource($memo);
    }

    /**
     * Update the memo's status to "Waiting for Modification".
     *
     * @param int $id Memo ID
     * @return JsonResponse
     */
    public function adminMemoSetWaitingForModify(int $id): JsonResponse
    {
        $memo = $this->repository->getMemoById($id);
        $user = $memo->user;

        // Update the memo's status
        $memo->status = \App\Enums\MemoStatusType::WAITING_FOR_MODIFY;
        $memo->is_appropriate = false;
        $memo->reviewed_by = MemoConst::ADMIN;
        // $memo->reviewed_at = now()->toDateTimeString();
        // $memo->status_at_review = $memo->status;
        $memo->fixed_after_warning = false;
        $memo->save();

        Mail::to($user->email)->send(new MemoEditRequest($memo));
        return response()->json(['message' => 'メモの修正リクエストを送信しました。']);
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

        return MemoResource::collection($memos);
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

        return MemoResource::collection($memos);
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

        return MemoResource::collection($memos);
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

        return MemoResource::collection($memos);
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

        return MemoResource::collection($memos);
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

        return MemoResource::collection($memos);
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

        return MemoResource::collection($memos);
    }
}
