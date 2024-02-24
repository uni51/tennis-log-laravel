<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MailSendTestController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
     // return view('welcome');
     return ['Laravel' => app()->version()];
});

Route::get('/mail_test', [MailSendTestController::class, 'send']);

require __DIR__.'/userAuth.php';
require __DIR__.'/adminAuth.php';
