<?php
namespace App\Services;

use App\Consts\Pagination;
use App\Http\Resources\MemoResource;
use App\Models\Memo;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class MemoService
{
    /**
     * @param int $userId
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function listMemoLinkedToUser(int $userId): AnonymousResourceCollection
    {
        try {
            $memos = Memo::with(['category:name,id'])
                    ->where('user_id', $userId)
                    ->orderBy('updated_at', 'desc')
                    ->paginate(Pagination::DEFAULT_PER_PAGE);
        } catch (Exception $e) {
            throw $e;
        }

        return MemoResource::collection($memos);
    }

    /**
     * @param int $userId
     * @param int $status
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function memoListByStatus(int $userId, int $status): AnonymousResourceCollection
    {
        try {
            $memos = Memo::with(['category:name,id'])
                ->where('user_id', $userId)
                ->where('status', $status)
                ->orderBy('updated_at', 'desc')
                ->paginate(Pagination::DEFAULT_PER_PAGE);

        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return MemoResource::collection($memos);
    }

    /**
     * @param int $userId
     * @param int $categoryId
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function memoListByCategory(int $userId, int $categoryId): AnonymousResourceCollection
    {
        try {
            $memos = Memo::with(['category:name,id'])
                ->where('user_id', $userId)
                ->where('category_id', $categoryId)
                ->orderBy('updated_at', 'desc')
                ->paginate(Pagination::DEFAULT_PER_PAGE);

        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return MemoResource::collection($memos);
    }

    /**
     * @param int $userId
     * @param string $tag
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function memoListByTag(int $userId, string $tag): AnonymousResourceCollection
    {
        try {
            $memos = Memo::with(['category:name,id'])
                ->where('user_id', $userId)
                ->whereHas('tags', function($q) use ($tag) {
                    $q->where('normalized', $tag);
                })
                ->orderBy('updated_at', 'desc')
                ->paginate(Pagination::DEFAULT_PER_PAGE);
            // Log::debug($memos);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return MemoResource::collection($memos);
    }

    /**
     * @param int $userId
     * @param int $categoryId
     * @param string $tag
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function memoListByCategoryAndTag(int $userId, int $categoryId, string $tag): AnonymousResourceCollection
    {
        try {
            $memos = Memo::with(['category:name,id'])
                ->where('user_id', $userId)
                ->where('category_id', $categoryId)
                ->whereHas('tags', function($q) use ($tag) {
                    $q->where('normalized', $tag);
                })
                ->orderBy('updated_at', 'desc')
                ->paginate(Pagination::DEFAULT_PER_PAGE);
            // Log::debug($memos);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return MemoResource::collection($memos);
    }

    /**
     * @param int $userId
     * @param string $keyword
     * @return LengthAwarePaginator
     */
    public function dashboardMemoSearch(int $userId, string $keyword): LengthAwarePaginator
    {
        $query = (new Memo)->newQuery();
        $query->where('user_id', $userId);

        // search title and description for provided strings (space-separated)
        if ($keyword) {
            $keywords = explode(' ', $keyword);

            $query->where(function($q) use ($keywords){
                foreach ($keywords as $keyword) {
                    $q->where(function($qq) use ($keyword) {
                        $qq->orWhere('title', 'like', '%'.$keyword.'%')
                            ->orWhere('body', 'like', '%'.$keyword.'%');
                    });
                }
            });
        }

        return $query->with(['category:name,id'])
                ->orderBy('updated_at', 'desc')
                ->paginate(Pagination::DEFAULT_PER_PAGE);
    }
}
