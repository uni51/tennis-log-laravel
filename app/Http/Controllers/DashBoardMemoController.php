<?php

namespace App\Http\Controllers;

use App\Http\Requests\Dashboard\DashboardMemosCategoryRequest;
use App\Http\Requests\Dashboard\DashboardMemosCategoryTagRequest;
use App\Http\Requests\Dashboard\DashboardMemosStatusRequest;
use App\Http\Requests\Dashboard\DashboardMemosTagRequest;
use App\Http\Requests\DashboardMemoSearchRequest;
use App\Http\Requests\MemoEditRequest;
use App\Http\Requests\MemoPostRequest;
use App\Http\Resources\MemoResource;
use App\Models\Memo;
use App\Services\DashboardMemoService;
use App\Services\MemoService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
     */
    public function search(DashboardMemoSearchRequest $request, MemoService $service): AnonymousResourceCollection
    {
        $memos = $service->dashboardMemoSearch(Auth::id(), $request->q);

        return MemoResource::collection($memos);
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
        return $service->memoListByStatus(Auth::id(), $request->status);
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
        return $service->memoListByCategory(Auth::id(), $request->categoryId);
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
        return $service->memoListByTag(Auth::id(), $request->tag);
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
        return $service->memoListByCategoryAndTag(Auth::id(), $request->categoryId, $request->tag);
    }

    /**
     * @param MemoPostRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function create(MemoPostRequest $request, DashboardMemoService $service): JsonResponse
    {
        $validated = $request->validated();
        return $service->create($validated);
    }

    public function show($id)
    {
        $memo = Memo::findOrFail($id);

        return new MemoResource($memo);
    }

    /**
     * @param MemoEditRequest $request
     * @param DashboardMemoService $service
     * @return JsonResponse
     * @throws Exception
     */
    public function edit(MemoEditRequest $request, DashboardMemoService $service): JsonResponse
    {
        $validated = $request->validated();
        return $service->edit($validated);
    }

    public function destroy($id)
    {
        $memo = Memo::findOrFail($id);
        $memo->delete();
        return response()->json(['message' => 'Memo deleted'], 200);
    }
}
