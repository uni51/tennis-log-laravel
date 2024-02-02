<?php

namespace App\Http\Controllers;

use App\Http\Requests\PublicMemos\PublicMemoShowRequest;
use App\Http\Requests\PublicMemoSearchRequest;
use App\Http\Resources\MemoResource;
use App\Models\Memo;
use App\Services\PublicMemoService;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PublicMemoController extends Controller
{
    /**
     * @param PublicMemoService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function allList(PublicMemoService $service): AnonymousResourceCollection
    {
        return $service->allList();
    }

    /**
     * @param PublicMemoShowRequest $request
     * @param PublicMemoService $service
     * @return MemoResource
     */
    public function show(PublicMemoShowRequest $request, PublicMemoService $service): MemoResource
    {
        $validated = $request->validated();
        return $service->show($validated['id']);
    }

    /**
     * @param PublicMemoSearchRequest $request
     * @param PublicMemoService $service
     * @return AnonymousResourceCollection
     */
    public function search(PublicMemoSearchRequest $request, PublicMemoService $service): AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->search($validated['q']);
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
