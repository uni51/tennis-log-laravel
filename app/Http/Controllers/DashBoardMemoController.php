<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\DashboardMemos\DashboardMemoDestroyRequest;
use App\Http\Requests\DashboardMemos\DashboardMemoEditRequest;
use App\Http\Requests\DashboardMemos\DashboardMemoShowRequest;
use App\Http\Requests\DashboardMemos\DashboardMemosByCategoryRequest;
use App\Http\Requests\DashboardMemos\DashboardMemosByCategoryTagRequest;
use App\Http\Requests\DashboardMemos\DashboardMemosByStatusRequest;
use App\Http\Requests\DashboardMemos\DashboardMemosByTagRequest;
use App\Http\Requests\DashboardMemoSearchRequest;
use App\Http\Requests\MemoPostRequest;
use App\Http\Resources\MemoResource;
use App\Services\DashboardMemoService;
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
     * @param DashboardMemoService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function list(DashboardMemoService $service): AnonymousResourceCollection
    {
        return $service->memoListByAuthUser(Auth::id());
    }

    /**
     * キーワードによる記事検索API
     *
     * @param DashboardMemoSearchRequest $request
     * @param DashboardMemoService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function search(DashboardMemoSearchRequest $request, DashboardMemoService $service): AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->dashboardMemoSearch(Auth::id(), $validated['q']);
    }

    /**
     * @param DashboardMemosByStatusRequest $request
     * @param DashboardMemoService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function memoListByStatus(
        DashboardMemosByStatusRequest $request,
        DashboardMemoService $service
    ): AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->memoListByStatus(Auth::id(), $validated['status']);
    }

    /**
     * カテゴリー別 記事一覧取得API
     *
     * @param DashboardMemosByCategoryRequest $request
     * @param DashboardMemoService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function memoListByCategory(
        DashboardMemosByCategoryRequest $request,
        DashboardMemoService $service
    ): AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->memoListByCategory(Auth::id(), $validated['category_id']);
    }

    /**
     * タグ別 記事一覧取得API
     *
     * @param DashboardMemosByTagRequest $request
     * @param DashboardMemoService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function memoListByTag(DashboardMemosByTagRequest $request, DashboardMemoService $service)
    : AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->memoListByTag($validated['tag'], Auth::id());
    }

    /**
     * カテゴリーおよびタグによる記事一覧取得API
     *
     * @param DashboardMemosByCategoryTagRequest $request
     * @param DashboardMemoService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function memoListByCategoryAndTag(
        DashboardMemosByCategoryTagRequest $request,
        DashboardMemoService $service
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
