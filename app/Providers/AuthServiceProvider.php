<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\Log;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
         // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

//        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
//            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
//        });

        Auth::viaRequest('firebase_cookie', function (Request $request) {
             $sessionCookie = Session::get('sessionCookie');

             $firebaseFactory = app()->make('firebase');
             $firebaseAuth = $firebaseFactory->createAuth();

             $requestCookieSession = $request->cookie('session');

             if ($sessionCookie !== $requestCookieSession) {
                 Session::put('sessionCookie', $requestCookieSession);
                 $verifiedIdToken = $firebaseAuth->verifySessionCookie($requestCookieSession, true);
                 $firebaseUid = $verifiedIdToken->claims()->get('sub');
             } else {
                 $firebaseUid = Session::get('uid');
             }

             $user = User::select()
                 ->where('firebase_uid', '=', $firebaseUid)
                 ->first();

            return $user;
        });
    }
}
