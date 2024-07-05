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
use App\Http\Controllers\Profile\ProfileController;
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
        ->name('get_profile_career');
    // 性別
    Route::get('/profile/gender', [GenderController::class, 'genderList'])
        ->name('get_profile_gender');
    // 年齢（範囲）
    Route::get('/profile/age_range', [AgeRangeController::class, 'ageLangeList'])
        ->name('get_profile_age_range');
    // 利き手
    Route::get('/profile/dominant_hand', [DominantHandController::class, 'dominantHandList'])
        ->name('get_profile_dominant_hand');
    // プレー頻度
    Route::get('/profile/play_frequency', [PlayFrequencyController::class, 'playFrequencyList'])
        ->name('get_profile_play_frequency');
    // テニスレベル
    Route::get('/profile/tennis_level', [TennisLevelController::class, 'tennisLevelList'])
        ->name('get_profile_tennis_level');

    // 公開中の記事一覧を取得するAPI
    Route::get('/public/memos', [PublicMemoController::class, 'publicMemoList'])
        ->name('get_public_memos');
    Route::get('/public/memos/search', [PublicMemoController::class, 'publicMemoSearch'])
        ->name('get_public_memos_search');
    Route::get('/public/memos/category/{category_id}', [PublicMemoController::class, 'publicMemoListByCategory'])
        ->name('get_public_memos_category');
    Route::get('/public/memos/tag/{tag}', [PublicMemoController::class, 'publicMemoListByTag'])
        ->name('get_public_memos_category');
    Route::get('/public/memos/category/{category_id}/tag/{tag}',
        [PublicMemoController::class, 'publicMemoListByCategoryAndTag'])
        ->name('get_public_memos_category_tag');
    Route::get('/public/memos/{id}', [PublicMemoController::class, 'publicMemoShow'])
        ->name('get_public_memos_id');

    // 公開中のニックネーム別（ユーザー別）記事一覧を取得するAPI
    Route::get('/public/{nickname}/memos', [NicknameMemoController::class, 'publicNicknameMemoList'])
        ->name('get_public_nickname_memos');
    Route::get('/public/{nickname}/memos/{id}', [NicknameMemoController::class, 'publicNicknameMemoShow'])
        ->name('get_public_nickname_memos_id');
    Route::get('/public/{nickname}/memos/category/{category_id}',
        [NicknameMemoController::class, 'publicNicknameMemoListByCategory'])
        ->name('get_public_nickname_memos_category');
    Route::get('/public/{nickname}/memos/tag/{tag}',
        [NicknameMemoController::class, 'publicNicknameMemoListByTag'])
        ->name('get_public_nickname_memos_tag');
    Route::get('/public/{nickname}/memos/category/{category_id}/tag/{tag}',
        [NicknameMemoController::class, 'publicNicknameMemoListByCategoryAndTag'])
        ->name('get_public_nickname_memos_category_tag');
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
        ->name('get_dashboard_memos');

    Route::get('/dashboard/memos/search', [DashBoardMemoController::class, 'dashboardMemoSearch'])
        ->name('get_dashboard_memos_search');

    Route::get('/dashboard/memos/status/{status}', [DashBoardMemoController::class, 'dashboardMemoListByStatus'])
        ->name('get_dashboard_memos_status');

    Route::get('/dashboard/memos/category/{category_id}', [DashBoardMemoController::class, 'dashboardMemoListByCategory'])
        ->name('get_dashboard_memos_category');

    Route::get('/dashboard/memos/tag/{tag}', [DashBoardMemoController::class, 'dashboardMemoListByTag'])
        ->name('get_dashboard_memos_tag');

    Route::get('/dashboard/memos/category/{category_id}/tag/{tag}',
        [DashBoardMemoController::class, 'memoListByCategoryAndTag'])
        ->name('get_dashboard_memos_category_tag');

    Route::get('/dashboard/memos/{id}', [DashBoardMemoController::class, 'dashboardMemoShow'])
        ->name('get_dashboard_memos_id');

    // メモの新規作成
    Route::post('/dashboard/memos', [DashBoardMemoController::class, 'dashboardMemoCreate'])
        ->name('post_dashboard_memos');

    Route::post('/dashboard/memos/upload-image', [DashBoardMemoController::class, 'dashboardMemoUploadImage'])
        ->name('post_dashboard_memos_upload-image');
    // メモの編集
    Route::post('/dashboard/memos/{id}', [DashBoardMemoController::class, 'dashboardMemoEdit'])
        ->name('post_dashboard_memos_id');

    Route::post('/dashboard/memos/delete/{id}', [DashBoardMemoController::class, 'dashboardMemoDestroy'])
        ->name('post_dashboard_memos_delete');

    // プロフィールの新規作成
    Route::post('/profile/create', [ProfileController::class, 'createProfile'])
        ->name('post_profile_create');

    Route::get('/user/delete', function () {
        $user = Auth::user();
        \App\Models\Memo::where('user_id', $user->id)->delete();
        return $user->delete();
    });

    // ユーザーに紐づく非公開の記事一覧を取得するAPI
    Route::get('/private/memos', [PrivateMemoController::class, 'list'])
        ->name('get_private_memos');
});

Route::group(['middleware' => 'auth:admin'], function () {
    Route::get('/admin', function (Request $request) {
        return $request->user();
    });

    Route::get('/admin/memos', [MemoManageController::class, 'adminMemoList'])
        ->name('get_admin_memos');
    Route::get('/admin/memos/waiting/review', [MemoManageController::class, 'adminMemoWaitingReviewList'])
        ->name('get_admin_memos_waiting_review');
    Route::get('/admin/memos/waiting/fix', [MemoManageController::class, 'adminMemoWaitingFixList'])
        ->name('get_admin_memos_waiting_fix');
    Route::get('/admin/memos/search', [MemoManageController::class, 'adminMemoSearch'])
        ->name('get_admin_memos_search');
    Route::get('/admin/memos/{id}', [MemoManageController::class, 'adminMemoShow'])
        ->name('get_admin_memos_id');
    Route::post('/admin/memos/approve/{id}', [MemoManageController::class, 'adminMemoApprove'])
        ->name('post_admin_memos_approve');
    Route::post('/admin/memos/request/fix/{id}', [MemoManageController::class, 'adminMemoRequestFix'])
        ->name('post_admin_memos_request_fix');
    Route::post('/admin/memos/delete/{id}', [MemoManageController::class, 'adminMemoDestroy'])
        ->name('post_admin_memos_delete');

    Route::get('/admin/memos/category/{category_id}', [MemoManageController::class, 'adminMemoListByCategory'])
        ->name('get_admin_memos_category');
    Route::get('/admin/memos/tag/{tag}', [MemoManageController::class, 'adminMemoListByTag'])
        ->name('get_admin_memos_tag');
    Route::get('/admin/memos/category/{category_id}/tag/{tag}',
        [MemoManageController::class, 'adminMemoListByCategoryAndTag'])
        ->name('get_admin_memos_category_tag');

    Route::get('/admin/{nickname}/memos', [MemoManageController::class, 'adminNicknameMemoList'])
        ->name('get_admin_nickname_memos');
    Route::get('/admin/{nickname}/memos/category/{category_id}',
        [MemoManageController::class, 'adminNicknameMemoListByCategory'])
        ->name('get_admin_nickname_memos_category');
    Route::get('/admin/{nickname}/memos/tag/{tag}',
        [MemoManageController::class, 'adminNicknameMemoListByTag'])
        ->name('get_admin_nickname_memos_tag');
    Route::get('/admin/{nickname}/memos/category/{category_id}/tag/{tag}',
        [MemoManageController::class, 'adminNicknameMemoListByCategoryAndTag'])
        ->name('get_admin_nickname_memos_category_tag');

    Route::get('/admin/users', [UserManageController::class, 'list'])
        ->name('get_admin_users');
    Route::post('/admin/users/disable', [UserManageController::class, 'disable'])
        ->name('post_admin_users_disable');
});
