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
    public function publicMemoList(PublicMemoService $service): AnonymousResourceCollection
    {
        return $service->publicMemoList();
    }

    /**
     * @param PublicMemoShowRequest $request
     * @param PublicMemoService $service
     * @return MemoResource
     * @throws Exception
     */
    public function publicMemoShow(PublicMemoShowRequest $request, PublicMemoService $service): MemoResource
    {
        $validated = $request->validated();
        return $service->publicMemoShow($validated['id']);
    }

    /**
     * @param PublicMemoSearchRequest $request
     * @param PublicMemoService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function publicMemoSearch(PublicMemoSearchRequest $request, PublicMemoService $service): AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->publicMemoSearch($validated['q']);
    }

    /**
     * @param PublicMemoListByCategoryRequest $request
     * @param PublicMemoService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function publicMemoListByCategory(PublicMemoListByCategoryRequest $request, PublicMemoService $service)
    : AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->publicMemoListByCategory($validated['category_id']);
    }

    /**
     * @param PublicMemoListByTagRequest $request
     * @param PublicMemoService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function publicMemoListByTag(PublicMemoListByTagRequest $request, PublicMemoService $service)
    : AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->publicMemoListByTag($validated['tag']);
    }

    /**
     * カテゴリーおよびタグによる記事一覧取得API
     *
     * @param PublicMemoListByCategoryTagRequest $request
     * @param PublicMemoService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function publicMemoListByCategoryAndTag(
        PublicMemoListByCategoryTagRequest $request,
        PublicMemoService $service
    ): AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->publicMemoListByCategoryAndTag($validated['category_id'], $validated['tag']);
    }
}
