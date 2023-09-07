<?php

namespace App\Http\Controllers\UserAuth;

use App\Http\Controllers\Controller;

use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth\CreateSessionCookie\FailedToCreateSessionCookie;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class FirebaseAuthController extends Controller
{
    private $auth;

    public function __construct()
    {
        $factory = (new Factory())
            ->withServiceAccount(file_get_contents(storage_path(env('FIREBASE_CREDENTIALS'))))
            ->withProjectId(env('FIREBASE_PROJECT'));

        $this->auth = $factory->createAuth();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        // Bearerから取得するようにする？
        $id_token = $request->input('idToken');

        try {
            $verifiedIdToken = $this->auth->verifyIdToken($id_token, 8000);
            // Log::debug('verifiedIdToken:' . $verifiedIdToken->toString());

            $firebaseUid = $verifiedIdToken->claims()->get('sub');
            Session::put('uid', $firebaseUid);
            $firebaseUser = $this->auth->getUser($firebaseUid);

            $user = User::select('users.*')
                ->where('firebase_uid', '=', $firebaseUid)
                ->first();

            if (is_null($user)) {
                $existSameEmailUser = User::where('email', $firebaseUser->email)->first();
                // 同一のEmailのユーザーが存在しない場合は、ユーザーを新規作成する
                if (is_null($existSameEmailUser)) {
                    $user = User::create([
                        'firebase_uid' => $firebaseUid,
                        'name' => $firebaseUser->displayName,
                        'nickname' => $firebaseUser->displayName,
                        'email' => $firebaseUser->email,
                    ]);
                } else {
                    $user = $existSameEmailUser;
                }
            }

            Auth::login($user);

            $oneWeek = new \DateInterval('P5D'); // 5日

            try {
                $sessionCookieString = $this->auth->createSessionCookie($verifiedIdToken, $oneWeek);
            } catch (FailedToCreateSessionCookie $error) {
                Log::debug($error->getMessage());
            }

            // TODO: セキュア属性の見直し
            Cookie::queue(
                'session',
                $sessionCookieString,
                60 * 24 * 5, // 5日
                '/',
                env('SESSION_DOMAIN'),
                false,  // secure属性
                true    // HttpOnly属性
            );

            return response()->json([
                'uid' => $firebaseUid,
                'name' => $firebaseUser->displayName,
            ]);

        } catch (FailedToVerifyToken $error) {
            Log::debug($error->getMessage());
            return response()->json([
                'error' => 'FailedToVerifyToken' . $error->getMessage(),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'error' => 'LoginError' . $error->getMessage(),
                Response::HTTP_UNAUTHORIZED,
            ]);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function logout(Request $request)
    {
        $sessionCookie = $request->cookie('session');

        try {
            Auth::guard('web')->logout();
            Cookie::queue(Cookie::forget('session'));
            Session::flush();
        } catch(\Exception $error) {
            Log::debug($error->getMessage());
            return response()->json([
                'error' => 'LogoutError' . $error->getMessage(),
                Response::HTTP_UNAUTHORIZED,
            ]);
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
