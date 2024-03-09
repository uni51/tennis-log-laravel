<?php
namespace App\Services;

use App\Http\Resources\MemoResource;
use App\Repositories\PublicMemoRepository;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;

class PublicMemoService
{
    private PublicMemoRepository $repository;

    /**
     * コンストラクタ
     *
     * @param PublicMemoRepository|null $repository
     */
    public function __construct(PublicMemoRepository $repository = null)
    {
        $this->repository = $repository ?? app(PublicMemoRepository::class);
    }


    /**
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function publicMemoList(): AnonymousResourceCollection
    {
        try {
            $memos = $this->repository->publicMemoList();
            // $memos = $this->repository->publicMemoListWithRedis();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return MemoResource::collection($memos);
    }

    /**
     * @param int $id
     * @return MemoResource
     * @throws Exception
     */
    public function publicMemoShow(int $id): MemoResource
    {
        try {
            $memo = $this->repository->publicMemoById($id);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }
        return new MemoResource($memo);
    }


    /**
     * @param string $input_keyword
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function publicMemoSearch(string $input_keyword): AnonymousResourceCollection
    {
        try {
            $memos = $this->repository->searchPublicMemoList($input_keyword);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }
        return MemoResource::collection($memos);
    }

    /**
     * @param $categoryId
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function publicMemoListByCategory($categoryId): AnonymousResourceCollection
    {
        try {
            $memos = $this->repository->publicMemoListByCategory($categoryId);
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
    public function publicMemoListByTag(string $tag): AnonymousResourceCollection
    {
        try {
            $memos = $this->repository->publicMemoListByTag($tag);
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
    public function publicMemoListByCategoryAndTag(int $categoryId, string $tag): AnonymousResourceCollection
    {
        try {
            $memos = $this->repository->publicMemoListByCategoryAndTag($categoryId, $tag);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return MemoResource::collection($memos);
    }
}
