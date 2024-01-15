<?php
namespace App\Services;

use App\Consts\Pagination;
use App\Http\Requests\DashboardMemoSearchRequest;
use App\Http\Resources\MemoResource;
use App\Models\Memo;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
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

    public function memoListByStatus($userId, $status)
    {
        try {
            $memos = Memo::with(['category:name,id'])
                ->where('user_id', $userId)
                ->where('status', $status)
                ->paginate(Pagination::DEFAULT_PER_PAGE);

        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return MemoResource::collection($memos);
    }

    public function memoListByCategory($userId, $categoryId)
    {
        try {
            $memos = Memo::with(['category:name,id'])
                ->where('user_id', $userId)
                ->where('category_id', $categoryId)
                ->paginate(Pagination::DEFAULT_PER_PAGE);

        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return MemoResource::collection($memos);
    }

    public function memoListByTag($userId, $tag)
    {
        try {
            $memos = Memo::with(['category:name,id'])
                ->where('user_id', $userId)
                ->whereHas('tags', function($q) use ($tag) {
                    $q->where('normalized', $tag);
                })
                ->paginate(Pagination::DEFAULT_PER_PAGE);
            // Log::debug($memos);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return MemoResource::collection($memos);
    }

    public function memoListByCategoryAndTag($userId, $categoryId, $tag)
    {
        try {
            $memos = Memo::with(['category:name,id'])
                ->where('user_id', $userId)
                ->where('category_id', $categoryId)
                ->whereHas('tags', function($q) use ($tag) {
                    $q->where('normalized', $tag);
                })
                ->paginate(Pagination::DEFAULT_PER_PAGE);
            // Log::debug($memos);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return MemoResource::collection($memos);
    }


    public function dashboardMemoSearch($userId, DashboardMemoSearchRequest $request){
        $query = (new Memo)->newQuery();
        $query->where('user_id', $userId);

        // search title and description for provided strings (space-separated)
        if ($request->q) {
            $keywords = explode(' ', $request->q);

            $query->where(function($q) use ($keywords){
                foreach ($keywords as $keyword) {
                    $q->where(function($qq) use ($keyword) {
                        $qq->orWhere('title', 'like', '%'.$keyword.'%')
                            ->orWhere('body', 'like', '%'.$keyword.'%');
                    });
                }
            });
        }

        return $query->with(['category:name,id'])->paginate(Pagination::DEFAULT_PER_PAGE);
    }
}
