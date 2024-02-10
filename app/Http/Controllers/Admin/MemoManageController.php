<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MemoManage\MemoManageNicknameListByCategoryRequest;
use App\Http\Requests\Admin\MemoManage\MemoManageNicknameListByTagRequest;
use App\Http\Requests\Admin\MemoManage\MemoManageNicknameListRequest;
use App\Http\Requests\Admin\MemoManage\MemoManageShowRequest;
use App\Http\Requests\Admin\MemoManage\MemoManageListByCategoryRequest;
use App\Http\Requests\NicknameMemos\NicknameMemoListByCategoryTagRequest;
use App\Http\Resources\MemoResource;
use App\Services\Admin\MemoManageService;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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
    public function list(MemoManageService $service): AnonymousResourceCollection
    {
        return $service->list();
    }

    /**
     * @param MemoManageShowRequest $request
     * @param MemoManageService $service
     * @return MemoResource
     */
    public function show(MemoManageShowRequest $request, MemoManageService $service): MemoResource
    {
        $validated = $request->validated();
        return $service->show($validated['id']);
    }

    public function memoListByCategory(
        MemoManageListByCategoryRequest $request,
        MemoManageService $service
    ): AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->memoListByCategory($validated['category_id']);
    }

    /**
     * @param MemoManageNicknameListRequest $request
     * @param MemoManageService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function userMemoList(MemoManageNicknameListRequest $request, MemoManageService $service): AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->userMemoList($validated['nickname']);
    }

    /**
     * @param MemoManageNicknameListByCategoryRequest $request
     * @param MemoManageService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function userMemoListByCategory(MemoManageNicknameListByCategoryRequest $request, MemoManageService $service)
    : AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->userMemoListByCategory($validated['nickname'], $validated['category_id']);
    }

    /**
     * タグ別 記事一覧取得API
     *
     * @param MemoManageNicknameListByTagRequest $request
     * @param MemoManageService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function userMemoListByTag(MemoManageNicknameListByTagRequest $request, MemoManageService $service)
    : AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->memoListByTag($validated['nickname'], $validated['tag']);
    }

    /**
     * @param NicknameMemoListByCategoryTagRequest $request
     * @param MemoManageService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function userMemoListByCategoryAndTag(
        NicknameMemoListByCategoryTagRequest $request,
        MemoManageService $service
    ): AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->memoListByCategoryAndTag($validated['nickname'], $validated['category_id'], $validated['tag']);
    }
}
