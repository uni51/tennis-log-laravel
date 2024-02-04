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
            $memos = $this->repository->getUserMemoList($nickname);
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
            $memo = $this->repository->getUserMemoDetail($nickname, $id);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return MemoResource::make($memo);
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
