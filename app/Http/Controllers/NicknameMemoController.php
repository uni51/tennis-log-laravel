<?php

namespace App\Http\Controllers;

use App\Http\Requests\NicknameMemos\NicknameUserMemoListRequest;
use App\Http\Requests\PublicMemos\PublicMemoListByCategoryRequest;
use App\Http\Requests\PublicMemos\PublicUserMemoDetailRequest;
use App\Http\Resources\MemoResource;
use App\Services\PublicMemoService;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class NicknameMemoController extends Controller
{
    /**
     * @param NicknameUserMemoListRequest $request
     * @param PublicMemoService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function userMemoList(NicknameUserMemoListRequest $request, PublicMemoService $service): AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->userMemoList($validated['nickname']);
    }

    /**
     * @param PublicUserMemoDetailRequest $request
     * @param PublicMemoService $service
     * @return MemoResource
     * @throws Exception
     */
    public function userMemoDetail(PublicUserMemoDetailRequest $request, PublicMemoService $service): MemoResource
    {
        $validated = $request->validated();
        return $service->userMemoDetail($validated['nickname'], $validated['id']);
    }

    public function memoListByCategory(PublicMemoListByCategoryRequest $request, PublicMemoService $service)
    {
        $validated = $request->validated();
        return $service->memoListByCategory($validated['category_id']);
    }

    public function userMemoListByCategory(PublicMemoService $service, $nickName, $categoryId)
    {
        return $service->userMemoListByCategory($nickName, $categoryId);
    }
}
