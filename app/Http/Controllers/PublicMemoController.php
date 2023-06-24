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
    public function allList(PublicMemoService $service): AnonymousResourceCollection
    {
        return $service->allList();
    }

    public function show($id)
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
    public function userMemoList(PublicMemoService $service, $nickName)
    {
        return $service->userMemoList($nickName);
    }

    public function userMemoDetail(PublicMemoService $service, $nickName, $memoId)
    {
        return $service->userMemoDetail($nickName, $memoId);
    }

    public function memoListByCategory(PublicMemoService $service, $categoryId)
    {
        return $service->memoListByCategory($categoryId);
    }

    public function userMemoListByCategory(PublicMemoService $service, $nickName, $categoryId)
    {
        return $service->userMemoListByCategory($nickName, $categoryId);
    }
}
