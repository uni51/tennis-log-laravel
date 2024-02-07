<?php

namespace App\Http\Controllers;

use App\Http\Requests\NicknameMemos\NicknameMemoListByCategoryTagRequest;
use App\Http\Requests\NicknameMemos\NicknameMemoListByTagRequest;
use App\Http\Requests\NicknameMemos\NicknameMemoListByCategoryRequest;
use App\Http\Requests\NicknameMemos\NicknameMemoListRequest;
use App\Http\Requests\NicknameMemos\NicknameMemoDetailRequest;
use App\Http\Resources\MemoResource;
use App\Services\DashboardMemoService;
use App\Services\NicknameMemoService;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class NicknameMemoController extends Controller
{
    /**
     * @param NicknameMemoListRequest $request
     * @param NicknameMemoService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function userMemoList(NicknameMemoListRequest $request, NicknameMemoService $service): AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->userMemoList($validated['nickname']);
    }

    /**
     * @param NicknameMemoDetailRequest $request
     * @param NicknameMemoService $service
     * @return MemoResource
     * @throws Exception
     */
    public function userMemoDetail(NicknameMemoDetailRequest $request, NicknameMemoService $service): MemoResource
    {
        $validated = $request->validated();
        return $service->userMemoDetail($validated['nickname'], $validated['id']);
    }

    /**
     * @param NicknameMemoListByCategoryRequest $request
     * @param NicknameMemoService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function userMemoListByCategory(NicknameMemoListByCategoryRequest $request, NicknameMemoService $service)
    : AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->userMemoListByCategory($validated['nickname'], $validated['category_id']);
    }

    /**
     * タグ別 記事一覧取得API
     *
     * @param NicknameMemoListByTagRequest $request
     * @param NicknameMemoService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function userMemoListByTag(NicknameMemoListByTagRequest $request, NicknameMemoService $service)
    : AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->memoListByTag($validated['nickname'], $validated['tag']);
    }

    /**
     * カテゴリーおよびタグによる記事一覧取得API
     *
     * @param NicknameMemoListByCategoryTagRequest $request
     * @param NicknameMemoService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function userMemoListByCategoryAndTag(
        NicknameMemoListByCategoryTagRequest $request,
        NicknameMemoService $service
    ): AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->memoListByCategoryAndTag($validated['nickname'], $validated['category_id'], $validated['tag']);
    }
}
