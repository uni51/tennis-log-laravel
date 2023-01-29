<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemoController;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;

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

Route::group(['middleware' => 'auth:sanctum'], function () {
//    Route::get('/user', function (Request $request) {
//        return $request->user();
//    });

    // ログインユーザー取得
    Route::get('/user', function() {
        $user = Auth::user();
        return $user ? new UserResource($user) : null;
    });

    Route::get('/memos', [MemoController::class, 'fetch']);
    Route::get('/memos/{id}', [MemoController::class, 'show']);
    Route::post('/memos', [MemoController::class, 'create']);
    Route::post('/memos/{id}', [MemoController::class, 'edit']);
    Route::post('/memos/{id}/delete', [MemoController::class, 'destroy']);
});

Route::group(['middleware' => 'auth:admin'], function () {
    Route::get('/admin', function (Request $request) {
        return $request->user();
    });
});
