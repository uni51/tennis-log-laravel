<?php
namespace App\Services;

use App\Http\Resources\MemoResource;
use App\Repositories\NicknameMemoRepository;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;

class NicknameMemoService
{
    private NicknameMemoRepository $repository;

    /**
     * コンストラクタ
     *
     * @param NicknameMemoRepository|null $repository
     */
    public function __construct(NicknameMemoRepository $repository = null)
    {
        $this->repository = $repository ?? app(NicknameMemoRepository::class);
    }

    /**
     * @param string $nickname
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function nicknameMemoList(string $nickname): AnonymousResourceCollection
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
     * @param int $id
     * @return MemoResource
     * @throws Exception
     */
    public function userMemoDetail(string $nickname, int $id): MemoResource
    {
        try {
            $memo = $this->repository->userMemoDetailByNickname($nickname, $id);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return MemoResource::make($memo);
    }

    /**
     * @param string $nickname
     * @param int $categoryId
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function nicknameMemoListByCategory(string $nickname, int $categoryId): AnonymousResourceCollection
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
    public function nicknameMemoListByTag(string $nickname, string $tag): AnonymousResourceCollection
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
    public function nicknameMemoListByCategoryAndTag(string $nickname, int $categoryId, string $tag): AnonymousResourceCollection
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
