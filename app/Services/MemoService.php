<?php
namespace App\Services;

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
                    ->paginate(6);
        } catch (Exception $e) {
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
                ->paginate(6);

        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return MemoResource::collection($memos);
    }
}
