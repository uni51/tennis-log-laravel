<?php
declare(strict_types=1);

namespace App\Providers;

use App\Providers\Guards\Firebase\FirebaseGuard;
use App\Providers\Guards\Firebase\LiveVerifyIdToken;
use App\Providers\Guards\Firebase\VerifyIdTokenInterface;
use Illuminate\Support\Facades\Auth;
use Kreait\Firebase\JWT\IdTokenVerifier;

final class FirebaseAuthenticationServiceProvider extends \Illuminate\Foundation\Support\Providers\AuthServiceProvider
{
    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Firebase PHP SDK(非公式)がEmulatorには対応していないので別途実装する必要があるので
         * サービスコンテナで環境ごとに差し替えします。
         */
        $this->app->bind(VerifyIdTokenInterface::class, LiveVerifyIdToken::class);

        /**
         * @see https://laravel.com/docs/8.x/authentication#adding-custom-guards
         */
        Auth::extend('firebase', function ($app, $name, array $config) {
            return new FirebaseGuard(app()->make(VerifyIdTokenInterface::class));
        });

        Auth::viaRequest('firebase', function ($request) {
            return app(FirebaseGuard::class)->user();
        });
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        /**
         * ID Tokenの検証クラスIdTokenVerifierをシングルトンで登録しておく
         */
        $this->app->singleton(IdTokenVerifier::class, function ($app) {
            $projectId = env('FIREBASE_PROJECT');
            return IdTokenVerifier::createWithProjectId($projectId);
        });
    }
}
