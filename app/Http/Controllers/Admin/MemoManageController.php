<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MemoManage\AdminMemoListByCategoryRequest;
use App\Http\Requests\Admin\MemoManage\AdminMemoListByCategoryTagRequest;
use App\Http\Requests\Admin\MemoManage\AdminMemoListByTagRequest;
use App\Http\Requests\Admin\MemoManage\AdminMemoRequestEditRequest;
use App\Http\Requests\Admin\MemoManage\AdminMemoSearchRequest;
use App\Http\Requests\Admin\MemoManage\AdminMemoShowRequest;
use App\Http\Requests\Admin\MemoManage\AdminNicknameMemoListByCategoryRequest;
use App\Http\Requests\Admin\MemoManage\AdminNicknameMemoListByCategoryTagRequest;
use App\Http\Requests\Admin\MemoManage\AdminNicknameMemoListByTagRequest;
use App\Http\Requests\Admin\MemoManage\AdminNicknameMemoListRequest;
use App\Http\Resources\MemoResource;
use App\Services\Admin\MemoManageService;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\JsonResponse;

/**
 * Class MemoManageController
 * Admin用メモ管理コントローラー
 *
 * @package App\Http\Controllers
 */
class MemoManageController extends Controller
{
    /**
     * @param MemoManageService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function adminMemoList(MemoManageService $service): AnonymousResourceCollection
    {
        return $service->adminMemoList();
    }

    /**
     * キーワードによる記事検索API
     *
     * @param AdminMemoSearchRequest $request
     * @param MemomanageService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function adminMemoSearch(AdminMemoSearchRequest $request, MemoManageService $service): AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->adminMemoSearch($validated['q']);
    }

    /**
     * @param AdminMemoShowRequest $request
     * @param MemoManageService $service
     * @return MemoResource
     */
    public function adminMemoShow(AdminMemoShowRequest $request, MemoManageService $service): MemoResource
    {
        $validated = $request->validated();
        return $service->adminMemoShow($validated['id']);
    }

    /**
     * @param AdminMemoRequestEditRequest $request
     * @param MemoManageService $service
     * @return JsonResponse
     */
    public function adminMemoRequestEdit(AdminMemoRequestEditRequest $request, MemoManageService $service): JsonResponse
    {
        $validated = $request->validated();
//        return $service->adminMemoRequestEdit($validated['id']);
        return $service->adminMemoSetWaitingForModify($validated['id']);
    }

    /**
     * @param AdminMemoListByCategoryRequest $request
     * @param MemoManageService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function adminMemoListByCategory(
        AdminMemoListByCategoryRequest $request,
        MemoManageService $service
    ): AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->adminMemoListByCategory($validated['category_id']);
    }

    /**
     * タグ別 記事一覧取得API
     *
     * @param AdminMemoListByTagRequest $request
     * @param MemoManageService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function adminMemoListByTag(AdminMemoListByTagRequest $request, MemoManageService $service)
    : AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->adminMemoListByTag($validated['tag']);
    }

    /**
     * @param AdminMemoListByCategoryTagRequest $request
     * @param MemoManageService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function adminMemoListByCategoryAndTag(
        AdminMemoListByCategoryTagRequest $request,
        MemoManageService $service
    ): AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->adminMemoListByCategoryAndTag($validated['category_id'], $validated['tag']);
    }

    /**
     * @param AdminNicknameMemoListRequest $request
     * @param MemoManageService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function adminNicknameMemoList(AdminNicknameMemoListRequest $request, MemoManageService $service): AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->adminNicknameMemoList($validated['nickname']);
    }

    /**
     * @param AdminNicknameMemoListByCategoryRequest $request
     * @param MemoManageService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function adminNicknameMemoListByCategory(AdminNicknameMemoListByCategoryRequest $request, MemoManageService $service)
    : AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->adminNicknameMemoListByCategory($validated['nickname'], $validated['category_id']);
    }

    /**
     * タグ別 記事一覧取得API
     *
     * @param AdminNicknameMemoListByTagRequest $request
     * @param MemoManageService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function adminNicknameMemoListByTag(AdminNicknameMemoListByTagRequest $request, MemoManageService $service)
    : AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->adminNicknameMemoListByTag($validated['nickname'], $validated['tag']);
    }

    /**
     * @param AdminNicknameMemoListByCategoryTagRequest $request
     * @param MemoManageService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function adminNicknameMemoListByCategoryAndTag(
        AdminNicknameMemoListByCategoryTagRequest $request,
        MemoManageService $service
    ): AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->adminNicknameMemoListByCategoryAndTag($validated['nickname'], $validated['category_id'], $validated['tag']);
    }
}
