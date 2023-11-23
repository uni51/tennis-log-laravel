<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemoEditRequest;
use App\Http\Requests\MemoPostRequest;
use App\Http\Resources\MemoResource;
use App\Models\Memo;
use App\Services\MemoService;
use Exception;
use Illuminate\Http\JsonResponse;
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
        // return Auth::guard('sanctum')->user();
        // return Auth::user();
        // ログインユーザーのID取得
        $userId = Auth::id();
        if (!$userId) {
            throw new Exception('未ログインです。');
        }
        return $service->listMemoLinkedToUser($userId);
    }


    public function memoListByCategory(MemoService $service, $categoryId)
    {
        // ログインユーザーのID取得
        $userId = Auth::id();
        if (!$userId) {
            throw new Exception('未ログインです。');
        }

        return $service->memoListByCategory($userId, $categoryId);
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
            // $memo->retag($request->tags);

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
            // $memo->retag($request->tags);

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
