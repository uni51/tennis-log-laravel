<?php

use App\Http\Controllers\UserAuth\AuthenticatedSessionController;
use App\Http\Controllers\UserAuth\EmailVerificationNotificationController;
use App\Http\Controllers\UserAuth\NewPasswordController;
use App\Http\Controllers\UserAuth\PasswordResetLinkController;
use App\Http\Controllers\UserAuth\RegisteredUserController;
use App\Http\Controllers\UserAuth\VerifyEmailController;
use App\Http\Controllers\OAuthProviderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Yutaro\LoginController;

Route::post('/register', [RegisteredUserController::class, 'store'])
                ->middleware('guest')
                ->name('register');

// SWR認証でのログイン
//Route::post('/login', [AuthenticatedSessionController::class, 'store'])
//                ->middleware('guest')
//                ->name('login');


// Recoilでのログイン
Route::post('/login', [LoginController::class, 'login'])->name('user.login');
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
                ->middleware('guest')
                ->name('password.email');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
                ->middleware('guest')
                ->name('password.store');

Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
                ->middleware(['auth', 'signed', 'throttle:6,1'])
                ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware(['auth', 'throttle:6,1'])
                ->name('verification.send');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
                ->middleware('auth')
                ->name('logout');

Route::middleware(['guest'])
    ->prefix('login')
    ->name('login')
    ->group(function () {
        Route::get('{provider}', [OAuthProviderController::class, 'index'])->name('provider');
        Route::get('{provider}/callback', [OAuthProviderController::class, 'store'])->name('provider.callback');
    });
