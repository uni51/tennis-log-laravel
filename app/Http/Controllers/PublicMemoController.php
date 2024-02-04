<?php

namespace App\Http\Controllers;

use App\Http\Requests\PublicMemos\PublicMemoListByCategoryTagRequest;
use App\Http\Requests\PublicMemos\PublicMemoListByCategoryRequest;
use App\Http\Requests\PublicMemos\PublicMemoListByTagRequest;
use App\Http\Requests\PublicMemos\PublicMemoShowRequest;
use App\Http\Requests\PublicMemoSearchRequest;
use App\Http\Resources\MemoResource;
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
     * @throws Exception
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
     * @throws Exception
     */
    public function search(PublicMemoSearchRequest $request, PublicMemoService $service): AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->search($validated['q']);
    }

    /**
     * @param PublicMemoListByCategoryRequest $request
     * @param PublicMemoService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function memoListByCategory(PublicMemoListByCategoryRequest $request, PublicMemoService $service)
    : AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->memoListByCategory($validated['category_id']);
    }

    /**
     * @param PublicMemoListByTagRequest $request
     * @param PublicMemoService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function memoListByTag(PublicMemoListByTagRequest $request, PublicMemoService $service)
    : AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->memoListByTag($validated['tag']);
    }

    /**
     * カテゴリーおよびタグによる記事一覧取得API
     *
     * @param PublicMemoListByCategoryTagRequest $request
     * @param PublicMemoService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function memoListByCategoryAndTag(
        PublicMemoListByCategoryTagRequest $request,
        PublicMemoService $service
    ): AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->memoListByCategoryAndTag($validated['category_id'], $validated['tag']);
    }
}
