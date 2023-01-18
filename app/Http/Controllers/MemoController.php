<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemoPostRequest;
use App\Http\Resources\MemoResource;
use App\Models\Memo;
use Exception;
use Illuminate\Support\Facades\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class MemoController extends Controller
{

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function fetch(Request $request)
    {
        // ログインユーザーのID取得
        $userId = Auth::id();
        if (!$userId) {
            throw new Exception('未ログインです。');
        }

        try {
            $memos = Memo::where('user_id', $userId)->get();
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
            // モデルクラスのインスタンス化
            $memo = new Memo();
            // パラメータのセット
            $memo->user_id = Auth::id();
            $memo->title = $request->title;
            $memo->body = $request->body;
            // モデルの保存
            $memo->save();

        } catch (Exception $e) {
            throw $e;
        }

        return response()->json([
            'message' => 'メモの登録に成功しました。'
        ], 201);
    }
}
