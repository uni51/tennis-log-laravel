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
     * タグ別 記事一覧取得API
     *
     * @param MemoManageListByTagRequest $request
     * @param MemoManageService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function memoListByTag(MemoManageListByTagRequest $request, MemoManageService $service)
    : AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->memoListByTag($validated['tag']);
    }

    public function memoListByCategoryAndTag(
        MemoManageListByCategoryTagRequest $request,
        MemoManageService $service
    ): AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->memoListByCategoryAndTag($validated['category_id'], $validated['tag']);
    }

    /**
     * @param MemoManageNicknameListRequest $request
     * @param MemoManageService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function nicknameMemoList(MemoManageNicknameListRequest $request, MemoManageService $service): AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->nicknameMemoList($validated['nickname']);
    }

    /**
     * @param MemoManageNicknameListByCategoryRequest $request
     * @param MemoManageService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function nicknameMemoListByCategory(MemoManageNicknameListByCategoryRequest $request, MemoManageService $service)
    : AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->nicknameMemoListByCategory($validated['nickname'], $validated['category_id']);
    }

    /**
     * タグ別 記事一覧取得API
     *
     * @param MemoManageNicknameListByTagRequest $request
     * @param MemoManageService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function nicknameMemoListByTag(MemoManageNicknameListByTagRequest $request, MemoManageService $service)
    : AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->nicknameMemoListByTag($validated['nickname'], $validated['tag']);
    }

    /**
     * @param MemoManageNicknameListByCategoryTagRequest $request
     * @param MemoManageService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function nicknameMemoListByCategoryAndTag(
        MemoManageNicknameListByCategoryTagRequest $request,
        MemoManageService $service
    ): AnonymousResourceCollection
    {
        $validated = $request->validated();
        return $service->nicknameMemoListByCategoryAndTag($validated['nickname'], $validated['category_id'], $validated['tag']);
    }
}
