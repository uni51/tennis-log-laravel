<?php

namespace App\Http\Controllers\UserAuth;

use App\Http\Controllers\Controller;

use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use Kreait\Firebase\Factory;
use App\Models\FirebaseLogin;
use App\Models\OAuthAccessTokens;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;

class FirebaseAuthController extends Controller
{

    private $auth;

    public function __construct()
    {
        $factory = (new Factory())
            ->withServiceAccount(file_get_contents(base_path(env('FIREBASE_CREDENTIALS'))))
            ->withProjectId(env('FIREBASE_PROJECT'));

        $this->auth = $factory->createAuth();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $id_token = $request->input('idToken');
        // Log::debug('Login idToken:' . $id_token);

        try {
            // $verifiedIdToken = $this->auth->verifyIdToken($id_token);
            $verifiedIdToken = $this->auth->verifyIdToken($id_token, false, 10000000);

             Log::debug('verifiedIdToken:' . $verifiedIdToken->toString());

            $firebaseUid = $verifiedIdToken->claims()->get('sub');
            $firebaseUser = $this->auth->getUser($firebaseUid);

            $user = User::select('users.*')
                ->where('firebase_logins.firebase_uid', '=', $firebaseUid)
                ->leftJoin('firebase_logins', 'users.id', '=', 'firebase_logins.user_id')
                ->first();

            // トランザクション開始
            DB::beginTransaction();

            if (is_null($user)) {
                $checkExistUser = User::where('email', $firebaseUser->email)->first();

                if (is_null($checkExistUser)) {
                    $user = User::create([
                        'name' => $firebaseUser->displayName,
                        'nickname' => $firebaseUser->displayName,
                        'email' => $firebaseUser->email,
                    ]);
                } else {
                    $user = $checkExistUser;
                }
                // Log::debug('Login User:' . $user);
            }

            $tokenResult = $user->createToken('Personal Access Token');

            // Log::debug('Login Token ID:' . $tokenResult->token->id);
            // Log::debug('Login accessToken:' . $tokenResult->accessToken);

            // Tokenの期限を1時間後に設定
            $expires_at = Carbon::now()->addHour();

            $firebaseLoginUser = FirebaseLogin::create([
                'user_id' => $user->id,
                'firebase_uid' => $firebaseUid,
                'token_id' => $tokenResult->token->id,
                'access_token' => $tokenResult->accessToken,
                // TODO: /api/userでの、firebase_logins.expires_atでのトークン期限チェックがまだ未実装
                'expires_at' => Carbon::parse($expires_at)->format('Y-m-d H:i:s'),
            ]);

            // Log::debug('FirebaseLoginUser:' . $firebaseLoginUser);

            // コミット
            DB::commit();

            $appToken = $tokenResult->accessToken ?? $user->access_token;

            // CookieにappTokenの値を有効期限1時間で設定
            Cookie::queue('appToken', $appToken, 60, '/', env('SESSION_DOMAIN'), false, false);

            return response()->json([
                'uid' => $firebaseUid,
                // ネームが設定されないことは基本的にはない筈だが、念の為の処理
                'name' => $firebaseUser->displayName ?? $firebaseUser->email,
                'token' => $appToken,
            ]);

        } catch (FailedToVerifyToken $e) {
            Log::debug($e->getMessage());
            return response()->json([
                'error' => 'FailedToVerifyToken' . $e->getMessage(),
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => 'LoginError' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function logout(Request $request): Response
    {
        $id_token = $request->headers->get('authorization');
        $token = trim(str_replace('Bearer', '', $id_token));

        try {
            // トランザクション開始
            DB::beginTransaction();

            $firebaseLoginUser = FirebaseLogin::where('access_token', $token)->first();
            $oauthAccessTokens = OAuthAccessTokens::where('id', $firebaseLoginUser->token_id)
                ->where('user_id', $firebaseLoginUser->user_id)
                ->first();

            // 以下の書き方だと、oauth_access_tokensテーブル内の、同一ユーザーIDに紐づくデータが全部消えてしまうので、個別にデータを削除する
            //auth('front_api')->user()->tokens()->delete();
            $firebaseLoginUser->delete();
            $oauthAccessTokens->delete();

            // コミット
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }

        // Log::debug('ログアウト');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->noContent();
    }

    public function user(Request $request)
    {
        // Log::debug('User:'.$request->user());
        // Log::debug('Auth User:'.Auth::guard('front_api')->user());
        $id_token = $request->headers->get('authorization');
        // Log::debug('id_token:'.$id_token);
        $token = trim(str_replace('Bearer', '', $id_token));
        $user = DB::table('users')
            ->select('users.id', 'users.nickname', 'users.name')
            ->leftJoin('firebase_logins', 'users.id', '=', 'firebase_logins.user_id')
            ->where('firebase_logins.access_token', '=', $token)
            ->first();

        if ($user) {
            $expiredUser = DB::table('users')
                ->select('users.id', 'users.nickname', 'users.name')
                ->leftJoin('firebase_logins', 'users.id', '=', 'firebase_logins.user_id')
                ->where('firebase_logins.access_token', '=', $token)
                // TODO: expires_at の名称を original_expires_at に変更すること
                ->where('firebase_logins.expires_at', '<=', Carbon::now())
                ->first();
        }

        // expires_atをチェックして、期限切れの場合は、ログアウトメソッドにリダイレクト
        if ($expiredUser) {
            return redirect()->route('logout');
        }

        return $user ? new UserResource($user) : null;
    }
}
