<?php
namespace App\Services;

use App\Http\Resources\MemoResource;
use App\Models\Memo;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;

class PublicMemoService
{
    /**
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function publicListMemo(): AnonymousResourceCollection
    {
        try {
            $memos = Memo::with(['category:name,id'])
                        ->where('status', 1)
                        ->paginate(5);
        } catch (Exception $e) {
            throw $e;
        }

        return MemoResource::collection($memos);
    }

    public function publicListMemoBelongsUser($userId): AnonymousResourceCollection
    {
        Log::debug($userId);
        try {
            $memos = Memo::with(['category:name,id'])
                ->where('user_id', $userId)
                ->where('status', 1)
                ->paginate(5);
        } catch (Exception $e) {
            throw $e;
        }

        return MemoResource::collection($memos);
    }
}
