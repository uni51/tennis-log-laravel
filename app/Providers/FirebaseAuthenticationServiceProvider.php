<?php
declare(strict_types=1);

namespace App\Providers;

use App\Providers\Guards\Firebase\FirebaseGuard;
use App\Providers\Guards\Firebase\LiveVerifyIdToken;
use App\Providers\Guards\Firebase\VerifyIdTokenInterface;
use Illuminate\Support\Facades\Auth;
use Kreait\Firebase\JWT\IdTokenVerifier;

/**
 * @See.) https://zenn.dev/manalink_dev/articles/manalink-laravel-firebase-auth
 */
final class FirebaseAuthenticationServiceProvider extends \Illuminate\Foundation\Support\Providers\AuthServiceProvider
{
    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(VerifyIdTokenInterface::class, LiveVerifyIdToken::class);

        /**
         * Auth::extendメソッドを実行することで、ドライバに対応したガード名でAuth::guard('api')などと使えるようになる。
         */
        Auth::extend('firebase', function ($app, $name, array $config) {
            return new FirebaseGuard(app()->make(VerifyIdTokenInterface::class));
        });

        /**
         * Auth::viaRequestメソッドを実行することで、middlewareメソッドにGuardを指定して、ルート単位での認証チェックが可能になる。
         */
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
