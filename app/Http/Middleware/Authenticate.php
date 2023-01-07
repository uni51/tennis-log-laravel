<?php

namespace App\Http\Middleware;

use App\Exceptions\ApiException;
use App\Models\User;
use Closure;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Contracts\Auth\Factory as Auth;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if ($this->auth->guard($guard)->guest()) {
            throw new ApiException('Requires authentication', 401);
        }

        $auth0User = $this->auth->guard('api')->user();

        // ユーザーテーブルに、同一のAuth0のIDがないかチェック
        $existUser = User::where('auth0_id', $auth0User->sub)->first();

        // ユーザーテーブルに、同一のAuth0のIDがない場合は、新規にユーザーテーブルにAuth0のIDとGoogleに登録しているnameを登録
        if (!$existUser) {
            $url = env('AUTH0_DOMAIN')."/userinfo";
            //接続
            try {
                $client = new Client();
                $response = $client->request('GET', $url, ['headers' => [
                    'authorization' => 'Bearer '.$request->bearerToken(),
                    'content-type' => 'application/json',
                ]]);
                $response = json_decode($response->getBody());

                User::create([
                    'auth0_id'   => $auth0User->sub,
                    'name'       => $response->name,
                ]);
            }
            catch (ClientException $e) {
                // TODO: Guzzleの接続エラー用の例外を投げる
                $response = $e->getResponse();
            }
        }

        return $next($request);
    }
}
