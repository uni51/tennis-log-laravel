<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MemoManage\MemoManageListByCategoryTagRequest;
use App\Http\Requests\Admin\MemoManage\MemoManageListByTagRequest;
use App\Http\Requests\Admin\MemoManage\MemoManageNicknameListByCategoryRequest;
use App\Http\Requests\Admin\MemoManage\MemoManageNicknameListByCategoryTagRequest;
use App\Http\Requests\Admin\MemoManage\MemoManageNicknameListByTagRequest;
use App\Http\Requests\Admin\MemoManage\MemoManageNicknameListRequest;
use App\Http\Requests\Admin\MemoManage\MemoManageShowRequest;
use App\Http\Requests\Admin\MemoManage\MemoManageListByCategoryRequest;
use App\Http\Requests\NicknameMemos\NicknameMemoListByCategoryTagRequest;
use App\Http\Requests\PublicMemos\PublicMemoListByCategoryTagRequest;
use App\Http\Requests\PublicMemos\PublicMemoListByTagRequest;
use App\Http\Resources\MemoResource;
use App\Services\Admin\MemoManageService;
use App\Services\NicknameMemoService;
use App\Services\PublicMemoService;
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
    public function adminMemoList(MemoManageService $service): AnonymousResourceCollection
    {
        return $service->adminMemoList();
    }

    /**
     * @param MemoManageShowRequest $request
     * @param MemoManageService $service
     * @return MemoResource
     */
    public function adminMemoShow(MemoManageShowRequest $request, MemoManageService $service): MemoResource
    {
        $validated = $request->validated();
        return $service->adminMemoShow($validated['id']);
    }

    /**
     * @param MemoManageListByCategoryRequest $request
     * @param MemoManageService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function adminMemoListByCategory(
        MemoManageListByCategoryRequest $request,
        MemoManageService $service
    ): AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->adminMemoListByCategory($validated['category_id']);
    }

    /**
     * タグ別 記事一覧取得API
     *
     * @param MemoManageListByTagRequest $request
     * @param MemoManageService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function adminMemoListByTag(MemoManageListByTagRequest $request, MemoManageService $service)
    : AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->adminMemoListByTag($validated['tag']);
    }

    /**
     * @param MemoManageListByCategoryTagRequest $request
     * @param MemoManageService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function adminMemoListByCategoryAndTag(
        MemoManageListByCategoryTagRequest $request,
        MemoManageService $service
    ): AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->adminMemoListByCategoryAndTag($validated['category_id'], $validated['tag']);
    }

    /**
     * @param MemoManageNicknameListRequest $request
     * @param MemoManageService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function adminNicknameMemoList(MemoManageNicknameListRequest $request, MemoManageService $service): AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->adminNicknameMemoList($validated['nickname']);
    }

    /**
     * @param MemoManageNicknameListByCategoryRequest $request
     * @param MemoManageService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function adminNicknameMemoListByCategory(MemoManageNicknameListByCategoryRequest $request, MemoManageService $service)
    : AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->adminNicknameMemoListByCategory($validated['nickname'], $validated['category_id']);
    }

    /**
     * タグ別 記事一覧取得API
     *
     * @param MemoManageNicknameListByTagRequest $request
     * @param MemoManageService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function adminNicknameMemoListByTag(MemoManageNicknameListByTagRequest $request, MemoManageService $service)
    : AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->adminNicknameMemoListByTag($validated['nickname'], $validated['tag']);
    }

    /**
     * @param MemoManageNicknameListByCategoryTagRequest $request
     * @param MemoManageService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function adminNicknameMemoListByCategoryAndTag(
        MemoManageNicknameListByCategoryTagRequest $request,
        MemoManageService $service
    ): AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->adminNicknameMemoListByCategoryAndTag($validated['nickname'], $validated['category_id'], $validated['tag']);
    }
}
