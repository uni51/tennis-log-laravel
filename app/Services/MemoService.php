<?php
namespace App\Services;

use App\Http\Resources\MemoResource;
use App\Models\Memo;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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
                    ->get();
        } catch (Exception $e) {
            throw $e;
        }

        return MemoResource::collection($memos);
    }
}
