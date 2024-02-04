<?php

namespace App\Http\Controllers;

use App\Http\Requests\PublicMemos\PublicMemoListByCategoryRequest;
use App\Http\Requests\PublicMemos\PublicMemoShowRequest;
use App\Http\Requests\PublicMemos\PublicUserMemoDetailRequest;
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
    {
        $validated = $request->validated();
        return $service->memoListByCategory($validated['category_id']);
    }
}
