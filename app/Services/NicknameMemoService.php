<?php
namespace App\Services;

use App\Consts\Pagination;
use App\Enums\MemoStatusType;
use App\Http\Resources\MemoResource;
use App\Models\Memo;
use App\Models\User;
use App\Repositories\NicknameMemoRepository;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
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
    public function userMemoList(string $nickname): AnonymousResourceCollection
    {
        try {
            $memos = $this->repository->userMemoList($nickname);
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
            $memo = $this->repository->userMemoDetail($nickname, $id);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return MemoResource::make($memo);
    }

    /**
     * @param string $nickName
     * @param int $categoryId
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function userMemoListByCategory(string $nickName, int $categoryId): AnonymousResourceCollection
    {
        try {
            $memos = $this->repository->userMemoListByCategory($nickName, $categoryId);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return MemoResource::collection($memos);
    }
}
