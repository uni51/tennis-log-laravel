<?php
namespace App\Http\Controllers;

use App\Http\Requests\DashboardMemos\DashboardMemoDestroyRequest;
use App\Http\Requests\DashboardMemos\DashboardMemoEditRequest;
use App\Http\Requests\DashboardMemos\DashboardMemoListByCategoryRequest;
use App\Http\Requests\DashboardMemos\DashboardMemoListByCategoryTagRequest;
use App\Http\Requests\DashboardMemos\DashboardMemoListByStatusRequest;
use App\Http\Requests\DashboardMemos\DashboardMemoListByTagRequest;
use App\Http\Requests\DashboardMemos\DashboardMemoSearchRequest;
use App\Http\Requests\DashboardMemos\DashboardMemoShowRequest;
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
    public function dashboardMemoList(DashboardMemoService $service): AnonymousResourceCollection
    {
        return $service->dashboardMemoListByAuthUser(Auth::id());
    }

    /**
     * キーワードによる記事検索API
     *
     * @param DashboardMemoSearchRequest $request
     * @param DashboardMemoService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function dashboardMemoSearch(DashboardMemoSearchRequest $request, DashboardMemoService $service): AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->dashboardMemoSearch(Auth::id(), $validated['q']);
    }

    /**
     * @param DashboardMemoListByStatusRequest $request
     * @param DashboardMemoService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function dashboardMemoListByStatus(
        DashboardMemoListByStatusRequest $request,
        DashboardMemoService $service
    ): AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->dashboardMemoListByStatus(Auth::id(), $validated['status']);
    }

    /**
     * カテゴリー別 記事一覧取得API
     *
     * @param DashboardMemoListByCategoryRequest $request
     * @param DashboardMemoService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function dashboardMemoListByCategory(
        DashboardMemoListByCategoryRequest $request,
        DashboardMemoService $service
    ): AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->dashboardMemoListByCategory(Auth::id(), $validated['category_id']);
    }

    /**
     * タグ別 記事一覧取得API
     *
     * @param DashboardMemoListByTagRequest $request
     * @param DashboardMemoService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function dashboardMemoListByTag(DashboardMemoListByTagRequest $request, DashboardMemoService $service)
    : AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->dashboardMemoListByTag(Auth::id(), $validated['tag']);
    }

    /**
     * カテゴリーおよびタグによる記事一覧取得API
     *
     * @param DashboardMemoListByCategoryTagRequest $request
     * @param DashboardMemoService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function memoListByCategoryAndTag(
        DashboardMemoListByCategoryTagRequest $request,
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
    public function dashboardMemoCreate(MemoPostRequest $request, DashboardMemoService $service): JsonResponse
    {
        $validated = $request->validated();
        return $service->dashboardMemoCreate($validated);
    }

    /**
     * @param DashboardMemoShowRequest $request
     * @param DashboardMemoService $service
     * @return MemoResource
     * @throws Exception
     */
    public function dashboardMemoShow(DashboardMemoShowRequest $request, DashboardMemoService $service): MemoResource
    {
        $user = Auth::user();
        $validated = $request->validated();
        return $service->dashboardMemoShow($validated['id'], $user);
    }

    /**
     * @param DashboardMemoEditRequest $request
     * @param DashboardMemoService $service
     * @return JsonResponse
     * @throws Exception
     */
    public function dashboardMemoEdit(DashboardMemoEditRequest $request, DashboardMemoService $service): JsonResponse
    {
        $user = Auth::user();
        $validated = $request->validated();
        return $service->dashboardMemoEdit($validated, $user);
    }

    /**
     * @param DashboardMemoDestroyRequest $request
     * @param DashboardMemoService $service
     * @return JsonResponse
     * @throws Exception
     */
    public function dashboardMemoDestroy(DashboardMemoDestroyRequest $request, DashboardMemoService $service): JsonResponse
    {
        $user = Auth::user();
        $validated = $request->validated();
        return $service->dashboardMemoDestroy($validated['id'], $user);
    }
}
