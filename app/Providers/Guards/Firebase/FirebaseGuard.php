<?php
declare(strict_types=1);

namespace App\Providers\Guards\Firebase;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;

class FirebaseGuard implements Guard
{

    // くりかえし利用された場合のキャッシュ用
    private ?Authenticatable $user = null;

    private $auth;

    /**
     * プロダクションなFirebaseに接続されているときと
     * ローカルのエミュレーターに繋がっているときで動作を変更しているため
     * Interfaceへの依存にして、実装クラスをそれぞれの環境ごとに実装している
     *
     *
     */
    public function __construct()
    {
        $factory = (new Factory())
            ->withServiceAccount(file_get_contents(base_path(env('FIREBASE_CREDENTIALS'))))
            ->withProjectId(env('FIREBASE_PROJECT'));

        $this->auth = $factory->createAuth();
    }

    /**
     * Get User by request claims.
     *
     * @return Authenticatable|null
     * @throws \Exception
     */
    public function user()
    {
        $token = \Illuminate\Support\Facades\Request::bearerToken();

        Log::debug($token);

        if (empty($token) || $token === 'undefined') {
            return null;
        }

        if (!is_null($this->user)) {
//            Log::debug(var_export($this->user));
            return $this->user;
        }

        /**
         * いずれの実装クラスでも、統一されたレスポンスクラスを返している
         */
        $verifyTokenResponse = $this->auth->verifyIdToken($token);

        Log::debug($verifyTokenResponse->claims()->get('sub'));

        if ($verifyTokenResponse) {
            $this->user = User::where('firebase_uid', $verifyTokenResponse->claims()->get('sub'))->first();
            return $this->user;
        }


        if ($verifyTokenResponse->isExpired(Carbon::now())) {
            Log::info('[Auth]トークンが期限切れのため401ステータスコードを返しました');
            throw new AuthenticationException('JWT token is expired.');
        }
    }

    // 以下略
    public function check()
    {
        // TODO: Implement check() method.
    }

    public function guest()
    {
        // TODO: Implement guest() method.
    }

    public function id()
    {
        // TODO: Implement id() method.
    }

    public function validate(array $credentials = [])
    {
        // TODO: Implement validate() method.
    }

    public function hasUser()
    {
        // TODO: Implement hasUser() method.
    }

    public function setUser(Authenticatable $user)
    {
        // TODO: Implement setUser() method.
    }
}
