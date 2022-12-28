<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\MessagesController;

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

Route::group(['prefix' => 'messages', 'middleware' => ['insert-metadata']], function () {
    Route::get('public', [MessagesController::class, 'showPublicMessage']);

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('protected', [MessagesController::class, 'showProtectedMessage']);

        Route::get('admin', [MessagesController::class, 'showAdminMessage'])->middleware('admin');
    });
});

//Route::apiResource('comments', CommentController::class);

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('comments', [CommentController::class, 'store']);
});


Route::get('test', function () {
    return \App\Models\Sample::first();
});

Route::post('test', function(Request $request) {
    $request->validate(['text' => 'required|string']);
    return \App\Models\Sample::create(['text' => $request->input('text')]);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
