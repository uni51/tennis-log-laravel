<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Consts\Token;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;
use Illuminate\Support\Carbon;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
         'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // PersonalAccessTokenの有効期限を設定する
        // Passport::personalAccessTokensExpireIn(Carbon::now()->addMinutes(Token::TokenValidMinutes));

//        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
//            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
//        });
    }

    public function register()
    {
        Passport::ignoreRoutes();
    }
}
