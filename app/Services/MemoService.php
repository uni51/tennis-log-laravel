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
}
