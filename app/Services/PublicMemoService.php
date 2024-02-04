<?php
namespace App\Services;

use App\Consts\Pagination;
use App\Enums\MemoStatusType;
use App\Http\Requests\PublicMemoSearchRequest;
use App\Http\Resources\MemoResource;
use App\Models\Memo;
use App\Models\User;
use App\Repositories\PublicMemoRepository;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
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
    public function allList(): AnonymousResourceCollection
    {
        try {
            $memos = $this->repository->allPublicList();
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
    public function show(int $id): MemoResource
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
    public function search(string $input_keyword): AnonymousResourceCollection
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
     * @param string $nickname
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function userMemoList(string $nickname): AnonymousResourceCollection
    {
        try {
            DB::beginTransaction();

            $user = User::where('nickname', $nickname)->firstOrFail();

            $memos = Memo::with(['category:name,id'])
                ->where('user_id', $user->id)
                ->where('status', MemoStatusType::getValue('公開中'))
                ->paginate(Pagination::DEFAULT_PER_PAGE);

            DB::commit();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
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
            DB::beginTransaction();

            $user = User::where('nickname', $nickname)->firstOrFail();

            $memos = Memo::with(['category:name,id'])
                ->where('user_id', $user->id)
                ->where('status', MemoStatusType::getValue('公開中'))
                ->where('id', $id)
                ->firstOrFail();

            DB::commit();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            throw $e;
        }

        return MemoResource::make($memos);
    }

    /**
     * @param $categoryId
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function memoListByCategory($categoryId): AnonymousResourceCollection
    {
        try {
            $memos = $this->repository->publicMemoListByCategory($categoryId);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return MemoResource::collection($memos);
    }

    public function userMemoListByCategory($nickName, $categoryId)
    {
        try {
            DB::beginTransaction();

            $user = User::where('nickname', $nickName)->firstOrFail();

            $memos = Memo::with(['category:name,id'])
                ->where('user_id', $user->id)
                ->where('category_id', $categoryId)
                ->where('status', MemoStatusType::getValue('公開中'))
                ->paginate(Pagination::DEFAULT_PER_PAGE);

            DB::commit();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            throw $e;
        }

        return MemoResource::collection($memos);
    }
}
