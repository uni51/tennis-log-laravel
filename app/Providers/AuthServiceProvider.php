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
//             $factory = (new Factory())
//                ->withServiceAccount(file_get_contents(storage_path(env('FIREBASE_CREDENTIALS'))))
//                ->withProjectId(env('FIREBASE_PROJECT'));
//             $firebaseAuth = $factory->createAuth();
//             $sessionCookie = $request->cookie('session');
//             $verifiedIdToken = $firebaseAuth->verifySessionCookie($sessionCookie, true);
//             $firebaseUid = $verifiedIdToken->claims()->get('sub');

            return User::select()
                // ->where('firebase_uid', '=', $firebaseUid)
                ->where('firebase_uid', '=', Session::get('uid'))
                ->first();
        });
    }
}
