<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemoPostRequest;
use App\Http\Requests\MemoEditRequest;
use App\Http\Resources\MemoResource;
use App\Models\Memo;
use Exception;
use Illuminate\Support\Facades\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MemoController extends Controller
{

    /**
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function fetch()
    {
        // ログインユーザーのID取得
        $userId = Auth::id();
        if (!$userId) {
            throw new Exception('未ログインです。');
        }

        try {
            $memos = Memo::with(['category:name,id'])->where('user_id', $userId)->get();
        } catch (Exception $e) {
            throw $e;
        }

        return MemoResource::collection($memos);

        // return Auth::guard('sanctum')->user();
        // return Auth::user();
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
