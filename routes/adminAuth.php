<?php

use App\Http\Controllers\AdminAuth\AuthenticatedSessionController;
use App\Http\Controllers\AdminAuth\EmailVerificationNotificationController;
use App\Http\Controllers\AdminAuth\NewPasswordController;
use App\Http\Controllers\AdminAuth\PasswordResetLinkController;
use App\Http\Controllers\AdminAuth\RegisteredAdminController;
use App\Http\Controllers\AdminAuth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::post('/admin/register', [RegisteredAdminController::class, 'store'])
                ->middleware('guest:admin')
                ->name('admin.register');

Route::post('/admin/login', [AuthenticatedSessionController::class, 'store'])
                ->middleware('guest:admin')
                ->name('admin.login');

Route::post('/admin/forgot-password', [PasswordResetLinkController::class, 'store'])
                ->middleware('guest:admin')
                ->name('admin.password.email');

Route::post('/admin/reset-password', [NewPasswordController::class, 'store'])
                ->middleware('guest:admin')
                ->name('admin.password.store');

Route::get('/admin/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
                ->middleware(['auth:admin', 'signed', 'throttle:6,1'])
                ->name('admin.verification.verify');

Route::post('/admin/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware(['auth:admin', 'throttle:6,1'])
                ->name('admin.verification.send');

Route::post('/admin/logout', [AuthenticatedSessionController::class, 'destroy'])
                ->middleware('auth:admin')
                ->name('admin.logout');


