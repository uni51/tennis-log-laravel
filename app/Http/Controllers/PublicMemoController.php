<?php

namespace App\Http\Controllers;

use App\Http\Resources\MemoResource;
use App\Models\Memo;
use App\Services\PublicMemoService;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PublicMemoController extends Controller
{

    /**
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function publicMemos(PublicMemoService $service): AnonymousResourceCollection
    {
        return $service->publicMemos();
    }

    public function publicMemoDetails($id)
    {
        $memo = Memo::where('status', 1)->findOrFail($id);

        return new MemoResource($memo);
    }

    /**
     * @param PublicMemoService $service
     * @param $nickName
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function publicMemosByNickname(PublicMemoService $service, $nickName): AnonymousResourceCollection
    {
        return $service->publicMemosByNickname($nickName);
    }

    public function publicMemoDetailsByNickname(PublicMemoService $service, $nickName, $memoId)
    {
        return $service->publicMemoDetailsByNickname($nickName, $memoId);
    }

    public function publicMemosByCategory(PublicMemoService $service, $categoryId)
    {
        return $service->publicMemosByCategory($categoryId);
    }

    public function publicMemoListByNicknameAndCategory(PublicMemoService $service, $nickName, $categoryId)
    {
        return $service->publicMemoListByNicknameAndCategory($nickName, $categoryId);
    }
}
