<?php

namespace App\Http\Controllers;

use App\Http\Requests\Dashboard\DashboardMemosCategoryRequest;
use App\Http\Requests\Dashboard\DashboardMemosStatusRequest;
use App\Http\Requests\DashboardMemoSearchRequest;
use App\Http\Requests\MemoEditRequest;
use App\Http\Requests\MemoPostRequest;
use App\Http\Resources\MemoResource;
use App\Models\Memo;
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
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function list(MemoService $service)
    {
        return $service->listMemoLinkedToUser(Auth::id());
    }

    public function search(DashboardMemoSearchRequest $request, MemoService $service)
    {
        $memos = $service->dashboardMemoSearch(Auth::id(), $request);

        return MemoResource::collection($memos);
    }

    public function memoListByStatus(DashboardMemosStatusRequest $request, MemoService $service)
    {
        return $service->memoListByStatus(Auth::id(), $request->status);
    }

    public function memoListByCategory(DashboardMemosCategoryRequest $request, MemoService $service)
    {
        return $service->memoListByCategory(Auth::id(), $request->categoryId);
    }

    public function memoListByTag(MemoService $service, $tag)
    {
        return $service->memoListByTag(Auth::id(), $tag);
    }
    public function memoListByCategoryAndTag(MemoService $service, $categoryId, $tag)
    {
        return $service->memoListByCategoryAndTag(Auth::id(), $categoryId, $tag);
    }

    /**
     * メモの登録
     * @param MemoPostRequest $request
     * @return JsonResponse
     */
    public function create(MemoPostRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            // モデルクラスのインスタンス化
            $memo = new Memo();
            // パラメータのセット
            $memo->user_id = Auth::id();
            $memo->category_id = $request->category_id;
            $memo->status = $request->status_id;
            $memo->title = $request->title;
            $memo->body = $request->body;
            // モデルの保存
            $memo->save();

            // メモとタグの紐付け
            if ($request->tags) {
                $memo->retag($request->tags);
            }

            $memo->retag($request->tags);

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return response()->json([
            'message' => 'メモの登録に成功しました。'
        ], 201);
    }

    public function show($id)
    {
        $memo = Memo::findOrFail($id);

        return new MemoResource($memo);
    }

    public function edit(MemoEditRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $memo = Memo::findOrFail($id);
            // モデルの保存
            $memo->update([
                $memo->category_id = $request->category_id,
                $memo->status = (int)$request->status_id,
                $memo->title = $request->title,
                $memo->body = $request->body,
            ]);

            // メモとタグの紐付け
             $memo->retag($request->tags);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return response()->json([
            'message' => 'メモの編集に成功しました。'
        ], 201);
    }

    public function destroy($id)
    {
        $memo = Memo::findOrFail($id);
        $memo->delete();
        return response()->json(['message' => 'Memo deleted'], 200);
    }
}
