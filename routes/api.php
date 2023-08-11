<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashBoardMemoController;
use App\Http\Controllers\PrivateMemoController;
use App\Http\Controllers\PublicMemoController;
use App\Http\Controllers\MemoController;
use App\Http\Controllers\FirebaseTestController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\UserResource;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/firebasetest/login_anonymous', [FirebaseTestController::class, 'loginAnonymous']);

Route::get('/memos/categories', [MemoController::class, 'getCategoryList'])->middleware('client');

Route::get('/memos/status', [MemoController::class, 'getStatusList']);

// 公開中の記事一覧を取得するAPI
Route::get('/public/memos', [PublicMemoController::class, 'allList']);
Route::get('/public/memos/{id}', [PublicMemoController::class, 'show']);
Route::get('/public/{nickname}/memos', [PublicMemoController::class, 'userMemoList']);
Route::get('/public/{nickname}/memos/{memoId}', [PublicMemoController::class, 'userMemoDetail']);
Route::get('/public/memos/category/{categoryId}', [PublicMemoController::class, 'memoListByCategory']);
Route::get('/public/{nickname}/memos/category/{categoryId}',
    [PublicMemoController::class, 'userMemoListByCategory']);

Route::group(['middleware' => 'client'], function () {
    // ログインユーザー取得
    Route::get('/user', function(Request $request) {
        $id_token = $request->headers->get('authorization');
        Log::debug('ルーティングapi id_token:'.$id_token);
        $token = trim(str_replace('Bearer', '', $id_token));
        $user = User::where('access_token', $token)->first();
        Log::debug('ルーティングapi auth_user:'.$user);
        return $user ? new UserResource($user) : null;
    });
});

Route::group(['middleware' => 'auth:sanctum'], function () {
//    Route::get('/user', function (Request $request) {
//        return $request->user();
//    });

    // ログインユーザー取得
//    Route::get('/user', function() {
//        $user = Auth::user();
//        return $user ? new UserResource($user) : null;
//    });

    Route::get('/user/delete', function() {
        $user = Auth::user();
        \App\Models\Memo::where('user_id', $user->id)->delete();
        return $user->delete();
    });

    // メモの公開・非公開を問わずに、ユーザーに紐づく記事一覧を取得するAPI
    Route::get('/dashboard/memos', [DashBoardMemoController::class, 'list']);
    Route::get('/dashboard/memos/category/{categoryId}', [DashBoardMemoController::class, 'memoListByCategory']);
    Route::get('/dashboard/memos/{id}', [DashBoardMemoController::class, 'show']);
    Route::post('/dashboard/memos', [DashBoardMemoController::class, 'create']);
    Route::post('/dashboard/memos/{id}', [DashBoardMemoController::class, 'edit']);
    Route::post('/dashboard/memos/{id}/delete', [DashBoardMemoController::class, 'destroy']);

    // ユーザーに紐づく非公開の記事一覧を取得するAPI
    Route::get('/private/memos', [PrivateMemoController::class, 'list']);
});

Route::group(['middleware' => 'auth:admin'], function () {
    Route::get('/admin', function (Request $request) {
        return $request->user();
    });
});
