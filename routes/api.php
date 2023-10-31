<?php

use App\Http\Controllers\DashBoardMemoController;
use App\Http\Controllers\FirebaseTestController;
use App\Http\Controllers\MemoController;
use App\Http\Controllers\PlayFrequencyController;
use App\Http\Controllers\PrivateMemoController;
use App\Http\Controllers\Profile\CareerController;
use App\Http\Controllers\Profile\GenderController;
use App\Http\Controllers\Profile\AgeRangeController;
use App\Http\Controllers\PublicMemoController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/memos/categories', [MemoController::class, 'getCategoryList']);
Route::get('/memos/status', [MemoController::class, 'getStatusList']);

Route::get('/career', [CareerController::class, 'careerList']);
Route::get('/gender', [GenderController::class, 'genderList']);
Route::get('/age_range', [AgeRangeController::class, 'ageLangeList']);
Route::get('/frequency', [PlayFrequencyController::class, 'playFrequencyList']);

// 公開中の記事一覧を取得するAPI
Route::get('/public/memos', [PublicMemoController::class, 'allList']);
Route::get('/public/memos/{id}', [PublicMemoController::class, 'show']);
Route::get('/public/{nickname}/memos', [PublicMemoController::class, 'userMemoList']);
Route::get('/public/{nickname}/memos/{memoId}', [PublicMemoController::class, 'userMemoDetail']);
Route::get('/public/memos/category/{categoryId}', [PublicMemoController::class, 'memoListByCategory']);
Route::get('/public/{nickname}/memos/category/{categoryId}',
    [PublicMemoController::class, 'userMemoListByCategory']);

// ログインユーザー取得

Route::group(['middleware' => 'auth:api', 'auth:firebase_cookie'], function () {
//    Route::get('/user', function (Request $request) {
//        return $request->user();
//    });
    Route::get('/user', function() {
        $user = Auth::user();
        return $user ? new UserResource($user) : null;
    });

    // メモの公開・非公開を問わずに、ユーザーに紐づく記事一覧を取得するAPI
    Route::get('/dashboard/memos', [DashBoardMemoController::class, 'list']);
            // ->middleware('cache.headers:private;max_age=0;etag;');
    Route::get('/dashboard/memos/category/{categoryId}', [DashBoardMemoController::class, 'memoListByCategory']);
    Route::get('/dashboard/memos/{id}', [DashBoardMemoController::class, 'show']);
    Route::post('/dashboard/memos', [DashBoardMemoController::class, 'create']);
    Route::post('/dashboard/memos/{id}', [DashBoardMemoController::class, 'edit']);
    Route::post('/dashboard/memos/{id}/delete', [DashBoardMemoController::class, 'destroy']);

    Route::get('/user/delete', function() {
        $user = Auth::user();
        \App\Models\Memo::where('user_id', $user->id)->delete();
        return $user->delete();
    });

    // ユーザーに紐づく非公開の記事一覧を取得するAPI
    Route::get('/private/memos', [PrivateMemoController::class, 'list']);
});

Route::group(['middleware' => 'auth:admin'], function () {
    Route::get('/admin', function (Request $request) {
        return $request->user();
    });
});
