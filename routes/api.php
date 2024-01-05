<?php

use App\Http\Controllers\DashBoardMemoController;
use App\Http\Controllers\FirebaseTestController;
use App\Http\Controllers\MemoController;
use App\Http\Controllers\PrivateMemoController;
use App\Http\Controllers\Profile\AgeRangeController;
use App\Http\Controllers\Profile\CareerController;
use App\Http\Controllers\Profile\DominantHandController;
use App\Http\Controllers\Profile\GenderController;
use App\Http\Controllers\Profile\PlayFrequencyController;
use App\Http\Controllers\Profile\TennisLevelController;
use App\Http\Controllers\PublicMemoController;
use App\Http\Controllers\Admin\UserManageController;
use App\Http\Controllers\Admin\MemoManageController;
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

// テニス歴
Route::get('/profile/career', [CareerController::class, 'careerList'])->name('profile.career');
// 性別
Route::get('/profile/gender', [GenderController::class, 'genderList'])->name('profile.gender');
// 年齢（範囲）
Route::get('/profile/age_range', [AgeRangeController::class, 'ageLangeList'])->name('profile.age_range');
// 利き手
Route::get('/profile/dominant_hand', [DominantHandController::class, 'dominantHandList'])
    ->name('profile.dominant_hand');
// プレー頻度
Route::get('/profile/play_frequency', [PlayFrequencyController::class, 'playFrequencyList'])
    ->name('profile.play_frequency');
// テニスレベル
Route::get('/profile/tennis_level', [TennisLevelController::class, 'tennisLevelList'])
    ->name('profile.tennis_level');

// 公開中の記事一覧を取得するAPI
Route::get('/public/memos', [PublicMemoController::class, 'allList']);
Route::get('/public/memos/search', [PublicMemoController::class, 'search']);
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
    Route::get('/dashboard/memos/search', [DashBoardMemoController::class, 'search']);
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
    Route::get('/admin/users', [UserManageController::class, 'list']);
    Route::get('/admin/memos', [MemoManageController::class, 'list']);
    Route::get('/admin/memos/{id}', [MemoManageController::class, 'show']);
});
