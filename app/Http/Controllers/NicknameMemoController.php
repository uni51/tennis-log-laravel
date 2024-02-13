<?php

namespace App\Http\Controllers;

use App\Http\Requests\NicknameMemos\NicknameMemoListByCategoryTagRequest;
use App\Http\Requests\NicknameMemos\NicknameMemoListByTagRequest;
use App\Http\Requests\NicknameMemos\NicknameMemoListByCategoryRequest;
use App\Http\Requests\NicknameMemos\NicknameMemoListRequest;
use App\Http\Requests\NicknameMemos\NicknameMemoDetailRequest;
use App\Http\Resources\MemoResource;
use App\Services\NicknameMemoService;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class NicknameMemoController extends Controller
{
    /**
     * @param NicknameMemoListRequest $request
     * @param NicknameMemoService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function publicNicknameMemoList(NicknameMemoListRequest $request, NicknameMemoService $service): AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->publicNicknameMemoList($validated['nickname']);
    }

    /**
     * @param NicknameMemoDetailRequest $request
     * @param NicknameMemoService $service
     * @return MemoResource
     * @throws Exception
     */
    public function publicNicknameMemoDetail(NicknameMemoDetailRequest $request, NicknameMemoService $service): MemoResource
    {
        $validated = $request->validated();
        return $service->publicNicknameMemoDetail($validated['nickname'], $validated['id']);
    }

    /**
     * @param NicknameMemoListByCategoryRequest $request
     * @param NicknameMemoService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function publicNicknameMemoListByCategory(NicknameMemoListByCategoryRequest $request, NicknameMemoService $service)
    : AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->publicNicknameMemoListByCategory($validated['nickname'], $validated['category_id']);
    }

    /**
     * タグ別 記事一覧取得API
     *
     * @param NicknameMemoListByTagRequest $request
     * @param NicknameMemoService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function publicNicknameMemoListByTag(NicknameMemoListByTagRequest $request, NicknameMemoService $service)
    : AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->publicNicknameMemoListByTag($validated['nickname'], $validated['tag']);
    }

    /**
     * カテゴリーおよびタグによる記事一覧取得API
     *
     * @param NicknameMemoListByCategoryTagRequest $request
     * @param NicknameMemoService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function publicNicknameMemoListByCategoryAndTag(
        NicknameMemoListByCategoryTagRequest $request,
        NicknameMemoService $service
    ): AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->publicNicknameMemoListByCategoryAndTag($validated['nickname'], $validated['category_id'], $validated['tag']);
    }
}
