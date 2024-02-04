<?php

namespace App\Http\Controllers;

use App\Http\Requests\DashboardMemos\DashboardMemoDestroyRequest;
use App\Http\Requests\DashboardMemos\DashboardMemoEditRequest;
use App\Http\Requests\DashboardMemos\DashboardMemoShowRequest;
use App\Http\Requests\DashboardMemos\DashboardMemosCategoryRequest;
use App\Http\Requests\DashboardMemos\DashboardMemosCategoryTagRequest;
use App\Http\Requests\DashboardMemos\DashboardMemosStatusRequest;
use App\Http\Requests\DashboardMemos\DashboardMemosTagRequest;
use App\Http\Requests\DashboardMemoSearchRequest;
use App\Http\Requests\MemoPostRequest;
use App\Http\Resources\MemoResource;
use App\Services\DashboardMemoService;
use App\Services\MemoService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

/**
 * Class DashBoardMemoController
 * ログインユーザーの作成した記事を取得するコントローラー
 *
 * @package App\Http\Controllers
 */
class DashBoardMemoController extends Controller
{
    /**
     * メモの公開・非公開を問わずに、そのユーザーに紐づく記事一覧を取得するAPI
     *
     * @param MemoService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function list(MemoService $service): AnonymousResourceCollection
    {
        return $service->listMemoLinkedToUser(Auth::id());
    }

    /**
     * キーワードによる記事検索API
     *
     * @param DashboardMemoSearchRequest $request
     * @param MemoService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function search(DashboardMemoSearchRequest $request, MemoService $service): AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->dashboardMemoSearch(Auth::id(), $validated['q']);
    }

    /**
     * @param DashboardMemosStatusRequest $request
     * @param MemoService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function memoListByStatus(
        DashboardMemosStatusRequest $request,
        MemoService $service
    ): AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->memoListByStatus(Auth::id(), $validated['status']);
    }

    /**
     * カテゴリー別 記事一覧取得API
     *
     * @param DashboardMemosCategoryRequest $request
     * @param MemoService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function memoListByCategory(
        DashboardMemosCategoryRequest $request,
        MemoService $service
    ): AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->memoListByCategory(Auth::id(), $validated['category_id']);
    }

    /**
     * タグ別 記事一覧取得API
     *
     * @param DashboardMemosTagRequest $request
     * @param MemoService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function memoListByTag(DashboardMemosTagRequest $request, MemoService $service): AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->memoListByTag(Auth::id(), $validated['tag']);
    }

    /**
     * カテゴリーおよびタグによる記事一覧取得API
     *
     * @param DashboardMemosCategoryTagRequest $request
     * @param MemoService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function memoListByCategoryAndTag(
        DashboardMemosCategoryTagRequest $request,
        MemoService $service
    ): AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->memoListByCategoryAndTag(Auth::id(), $validated['category_id'], $validated['tag']);
    }

    /**
     * @param MemoPostRequest $request
     * @param DashboardMemoService $service
     * @return JsonResponse
     * @throws Exception
     */
    public function create(MemoPostRequest $request, DashboardMemoService $service): JsonResponse
    {
        $validated = $request->validated();
        return $service->create($validated);
    }

    /**
     * @param DashboardMemoShowRequest $request
     * @param DashboardMemoService $service
     * @return MemoResource
     * @throws Exception
     */
    public function show(DashboardMemoShowRequest $request, DashboardMemoService $service): MemoResource
    {
        $user = Auth::user();
        $validated = $request->validated();
        return $service->show($validated['id'], $user);
    }

    /**
     * @param DashboardMemoEditRequest $request
     * @param DashboardMemoService $service
     * @return JsonResponse
     * @throws Exception
     */
    public function edit(DashboardMemoEditRequest $request, DashboardMemoService $service): JsonResponse
    {
        $user = Auth::user();
        $validated = $request->validated();
        return $service->edit($validated, $user);
    }

    /**
     * @param DashboardMemoDestroyRequest $request
     * @param DashboardMemoService $service
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(DashboardMemoDestroyRequest $request, DashboardMemoService $service): JsonResponse
    {
        $user = Auth::user();
        $validated = $request->validated();
        return $service->destroy($validated['id'], $user);
    }
}
