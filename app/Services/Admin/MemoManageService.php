<?php
namespace App\Services\Admin;

use App\Http\Resources\Admin\MemoManageResource;
use App\Http\Resources\MemoResource;
use App\Repositories\Admin\MemoManageRepository;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;

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
    public function list(): AnonymousResourceCollection
    {
        try {
            $memos = $this->repository->getMemoList();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return MemoManageResource::collection($memos);
    }

    /**
     * @param int $id
     * @return MemoResource
     */
    public function show(int $id): MemoResource
    {
        $memo = $this->repository->getMemoById($id);

        return new MemoResource($memo);
    }

    /**
     * @param int $categoryId
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function memoListByCategory(int $categoryId): AnonymousResourceCollection
    {
        try {
            $memos = $this->repository->memoListByCategory($categoryId);
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
    public function memoListByTag(string $tag): AnonymousResourceCollection
    {
        try {
            $memos = $this->repository->memoListByTag($tag);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return MemoResource::collection($memos);
    }

    public function memoListByCategoryAndTag(int $categoryId, string $tag): AnonymousResourceCollection
    {
        try {
            $memos = $this->repository->memoListByCategoryAndTag($categoryId, $tag);
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
    public function userMemoList(string $nickname): AnonymousResourceCollection
    {
        try {
            $memos = $this->repository->nicknameMemoList($nickname);
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
    public function userMemoListByCategory(string $nickname, int $categoryId): AnonymousResourceCollection
    {
        try {
            $memos = $this->repository->nicknameMemoListByCategory($nickname, $categoryId);
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
    public function memoListByNicknameAndTag(string $nickname, string $tag): AnonymousResourceCollection
    {
        try {
            $memos = $this->repository->nicknameMemoListByTag($nickname, $tag);
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
    public function userMemoListByCategoryAndTag(string $nickname, int $categoryId, string $tag): AnonymousResourceCollection
    {
        try {
            $memos = $this->repository->nicknameMemoListByCategoryAndTag($nickname, $categoryId, $tag);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return MemoResource::collection($memos);
    }
}
