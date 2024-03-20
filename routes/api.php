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
use App\Http\Controllers\NicknameMemoController;
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

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/firebasetest/login_anonymous', [FirebaseTestController::class, 'loginAnonymous']);

    Route::get('/memos/categories', [MemoController::class, 'getCategoryList']);
    Route::get('/memos/status', [MemoController::class, 'getStatusList']);

    // テニス歴
    Route::get('/profile/career', [CareerController::class, 'careerList'])
        ->name('get.profile.career');
    // 性別
    Route::get('/profile/gender', [GenderController::class, 'genderList'])
        ->name('get.profile.gender');
    // 年齢（範囲）
    Route::get('/profile/age_range', [AgeRangeController::class, 'ageLangeList'])
        ->name('get.profile.age_range');
    // 利き手
    Route::get('/profile/dominant_hand', [DominantHandController::class, 'dominantHandList'])
        ->name('get.profile.dominant_hand');
    // プレー頻度
    Route::get('/profile/play_frequency', [PlayFrequencyController::class, 'playFrequencyList'])
        ->name('get.profile.play_frequency');
    // テニスレベル
    Route::get('/profile/tennis_level', [TennisLevelController::class, 'tennisLevelList'])
        ->name('get.profile.tennis_level');

    // 公開中の記事一覧を取得するAPI
    Route::get('/public/memos', [PublicMemoController::class, 'publicMemoList'])
        ->name('get.public.memos');
    Route::get('/public/memos/search', [PublicMemoController::class, 'publicMemoSearch'])
        ->name('get.public.memos.search');
    Route::get('/public/memos/category/{category_id}', [PublicMemoController::class, 'publicMemoListByCategory'])
        ->name('get.public.memos.category');
    Route::get('/public/memos/tag/{tag}', [PublicMemoController::class, 'publicMemoListByTag'])
        ->name('get.public.memos.category');
    Route::get('/public/memos/category/{category_id}/tag/{tag}',
        [PublicMemoController::class, 'publicMemoListByCategoryAndTag'])
        ->name('get.public.memos.category.tag');
    Route::get('/public/memos/{id}', [PublicMemoController::class, 'publicMemoShow'])
        ->name('get.public.memos.id');

    // 公開中のニックネーム別（ユーザー別）記事一覧を取得するAPI
    Route::get('/public/{nickname}/memos', [NicknameMemoController::class, 'publicNicknameMemoList'])
        ->name('get.public.nickname.memos');
    Route::get('/public/{nickname}/memos/{id}', [NicknameMemoController::class, 'publicNicknameMemoShow'])
        ->name('get.public.nickname.memos.id');
    Route::get('/public/{nickname}/memos/category/{category_id}',
        [NicknameMemoController::class, 'publicNicknameMemoListByCategory'])
        ->name('get.public.nickname.memos.category');
    Route::get('/public/{nickname}/memos/tag/{tag}',
        [NicknameMemoController::class, 'publicNicknameMemoListByTag'])
        ->name('get.public.nickname.memos.tag');
    Route::get('/public/{nickname}/memos/category/{category_id}/tag/{tag}',
        [NicknameMemoController::class, 'publicNicknameMemoListByCategoryAndTag'])
        ->name('get.public.nickname.memos.category.tag');
    });


Route::group(['middleware' => 'auth:api', 'auth:firebase_cookie'], function () {
//    Route::get('/user', function (Request $request) {
//        return $request->user();
//    });
    // ログインユーザー取得
    Route::get('/user', function() {
        $user = Auth::user();
        return $user ? new UserResource($user) : null;
    });

    // メモの公開・非公開を問わずに、ユーザーに紐づく記事一覧を取得するAPI
    Route::get('/dashboard/memos', [DashBoardMemoController::class, 'dashboardMemoList'])
        ->name('get.dashboard.memos');

    Route::get('/dashboard/memos/search', [DashBoardMemoController::class, 'dashboardMemoSearch'])
        ->name('get.dashboard.memos.search');

    Route::get('/dashboard/memos/status/{status}', [DashBoardMemoController::class, 'dashboardMemoListByStatus'])
        ->name('get.dashboard.memos.status');

    Route::get('/dashboard/memos/category/{category_id}', [DashBoardMemoController::class, 'dashboardMemoListByCategory'])
        ->name('get.dashboard.memos.category');

    Route::get('/dashboard/memos/tag/{tag}', [DashBoardMemoController::class, 'dashboardMemoListByTag'])
        ->name('get.dashboard.memos.tag');

    Route::get('/dashboard/memos/category/{category_id}/tag/{tag}',
        [DashBoardMemoController::class, 'memoListByCategoryAndTag'])
        ->name('get.dashboard.memos.category.tag');

    Route::get('/dashboard/memos/{id}', [DashBoardMemoController::class, 'dashboardMemoShow'])
        ->name('get.dashboard.memos.id');

    // メモの新規作成
    Route::post('/dashboard/memos', [DashBoardMemoController::class, 'dashboardMemoCreate'])
        ->name('post.dashboard.memos');
    Route::post('/dashboard/memos/upload-image', [DashBoardMemoController::class, 'dashboardMemoUploadImage'])
        ->name('post.dashboard.memos.id');
    // メモの編集
    Route::post('/dashboard/memos/{id}', [DashBoardMemoController::class, 'dashboardMemoEdit'])
        ->name('post.dashboard.memos.id');

    Route::post('/dashboard/memos/{id}/delete', [DashBoardMemoController::class, 'dashboardMemoDestroy'])
        ->name('post.dashboard.memos.id.delete');

    Route::get('/user/delete', function () {
        $user = Auth::user();
        \App\Models\Memo::where('user_id', $user->id)->delete();
        return $user->delete();
    });

    // ユーザーに紐づく非公開の記事一覧を取得するAPI
    Route::get('/private/memos', [PrivateMemoController::class, 'list'])
        ->name('get.private.memos');
});

Route::group(['middleware' => 'auth:admin'], function () {
    Route::get('/admin', function (Request $request) {
        return $request->user();
    });

    Route::get('/admin/memos', [MemoManageController::class, 'adminMemoList'])
        ->name('get.admin.memos');
    Route::get('/admin/memos/search', [MemoManageController::class, 'adminMemoSearch'])
        ->name('get.admin.memos.search');
    Route::get('/admin/memos/{id}', [MemoManageController::class, 'adminMemoShow'])
        ->name('get.admin.memos.id');
    Route::post('/admin/memos/{id}/request-modify', [MemoManageController::class, 'adminMemoRequestModify'])
        ->name('get.admin.memos.id.request-edit');

    Route::get('/admin/memos/category/{category_id}', [MemoManageController::class, 'adminMemoListByCategory'])
        ->name('get.admin.memos.category');
    Route::get('/admin/memos/tag/{tag}', [MemoManageController::class, 'adminMemoListByTag'])
        ->name('get.admin.memos.category');
    Route::get('/admin/memos/category/{category_id}/tag/{tag}',
        [MemoManageController::class, 'adminMemoListByCategoryAndTag'])
        ->name('get.admin.memos.category.tag');

    Route::get('/admin/{nickname}/memos', [MemoManageController::class, 'adminNicknameMemoList'])
        ->name('get.admin.nickname.memos');
    Route::get('/admin/{nickname}/memos/category/{category_id}',
        [MemoManageController::class, 'adminNicknameMemoListByCategory'])
        ->name('get.admin.nickname.memos.category');
    Route::get('/admin/{nickname}/memos/tag/{tag}',
        [MemoManageController::class, 'adminNicknameMemoListByTag'])
        ->name('get.admin.nickname.memos.tag');
    Route::get('/admin/{nickname}/memos/category/{category_id}/tag/{tag}',
        [MemoManageController::class, 'adminNicknameMemoListByCategoryAndTag'])
        ->name('get.admin.nickname.memos.category.tag');

    Route::get('/admin/users', [UserManageController::class, 'list'])
        ->name('get.admin.users');
    Route::post('/admin/users/disable', [UserManageController::class, 'disable'])
        ->name('post.admin.users.disable');
});
