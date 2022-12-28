<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CommentController;

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

Route::apiResource('comments', CommentController::class);

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
