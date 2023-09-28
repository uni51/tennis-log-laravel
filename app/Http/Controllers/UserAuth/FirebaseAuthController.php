<?php

namespace App\Http\Controllers\UserAuth;

use App\Http\Controllers\Controller;

use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
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
        $firebaseFactory = app()->make('firebase');
        $this->auth = $firebaseFactory->createAuth();
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
            $verifiedIdToken = $this->auth->verifyIdToken($id_token, true, $leewayInSeconds = 60);
            // Log::debug('verifiedIdToken:' . $verifiedIdToken->toString());

            $firebaseUid = $verifiedIdToken->claims()->get('sub');
            Session::put('uid', $firebaseUid);
            $firebaseUser = $this->auth->getUser($firebaseUid);

            $user = User::select('users.*')
                ->where('firebase_uid', '=', $firebaseUid)
                ->where('email', '=', $firebaseUser->email)
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
                    // 同一のEmailのユーザーが存在する場合は、FirebaseのUIDを更新
                    $user = $existSameEmailUser;
                    $user->firebase_uid = $firebaseUid;
                    $user->save();
                }
            }

            Auth::login($user);

            $oneWeek = new \DateInterval('P5D'); // 5日

            try {
                $sessionCookieString = $this->auth->createSessionCookie($verifiedIdToken, $oneWeek);
            } catch (FailedToCreateSessionCookie $error) {
                Log::debug($error->getMessage());
                return response()->json([
                    'error' => 'createSessionCookie:' . $error->getMessage(),
                    Response::HTTP_UNAUTHORIZED,
                ]);
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
                'error' => 'FailedToVerifyToken:' . $error->getMessage(),
                Response::HTTP_UNAUTHORIZED,
            ]);
        } catch (\Exception $error) {
            Log::debug($error->getMessage());
            return response()->json([
                'error' => 'LoginError:' . $error->getMessage(),
                Response::HTTP_UNAUTHORIZED,
            ]);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        Cookie::queue(Cookie::forget('session'));
        Session::flush();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
