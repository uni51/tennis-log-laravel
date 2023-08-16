<?php

namespace App\Http\Controllers\UserAuth;

use App\Http\Controllers\Controller;

use App\Models\FirebaseLogin;
use App\Models\OAuthAccessTokens;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use Kreait\Firebase\Factory;

class  FirebaseAuthController extends Controller
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
     * @param  Request  $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
         $id_token = $request->input('idToken');

        Log::debug('Login idToken:'.$id_token);

        try {
            $verifiedIdToken = $this->auth->verifyIdToken($id_token);
        } catch (FailedToVerifyToken $e) {
            Log::debug($e->getMessage());
            return response()->json([
                'error' => $e->getMessage(),
            ]);
        }

        $firebaseUid = $verifiedIdToken->claims()->get('sub');
        $firebaseUser = $this->auth->getUser($firebaseUid);

//        $user = User::where('firebase_uid', $firebaseUid)->first();

//        $user = DB::table('users')
//                    ->leftJoin('firebase_logins', 'users.id', '=', 'firebase_logins.user_id')
//                    ->where('firebase_logins.firebase_uid', '=', $firebaseUid)
//                    ->first();
        $user = User::select('users.*')
                ->where('firebase_logins.firebase_uid', '=', $firebaseUid)
                ->leftJoin('firebase_logins', 'users.id', '=', 'firebase_logins.user_id')
                ->first();

        if (is_null($user)) {
            $checkExistUser = User::where('email', $firebaseUser->email)->first();

            if(is_null($checkExistUser)) {
                $user = User::create([
                    'name' => $firebaseUser->displayName,
                    'nickname' => $firebaseUser->displayName,
                    'email' => $firebaseUser->email,
                ]);
            } else {
                $user = $checkExistUser;
            }

            Log::debug('user 02:'.$user);

        }

        $tokenResult = $user->createToken('Personal Access Token');

        Log::debug('Login Token ID:'.$tokenResult->token->id);
        Log::debug('Login accessToken:'.$tokenResult->accessToken);

        $expires_at = Carbon::now()->addWeeks(1);

        $firebaseLoginUser = FirebaseLogin::create([
            'user_id' => $user->id,
            'firebase_uid' => $firebaseUid,
            'token_id' => $tokenResult->token->id,
            'access_token' => $tokenResult->accessToken,
            'expires_at' => Carbon::parse(
                $expires_at
            )->toDateTimeString()
        ]);

        Log::debug('firebaseLoginUser 03:'.$firebaseLoginUser);


        return response()->json([
            'uid' => $firebaseUid,
            // ネームが設定されないことは基本的にはない筈だが、念の為の処理
            'name' => $firebaseUser->displayName ?? $firebaseUser->email,
            'token' => $tokenResult->accessToken ?? $user->access_token,
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function logout(Request $request): Response
    {
        //auth('front_api')->user()->tokens()->delete();

        $id_token = $request->headers->get('authorization');
        $token = trim(str_replace('Bearer', '', $id_token));

        $firebaseLoginUser = FirebaseLogin::where('access_token', $token)->first();

        $oauthAccessTokens = OAuthAccessTokens::where('id', $firebaseLoginUser->token_id)
                            ->where('user_id', $firebaseLoginUser->user_id)
                            ->first();

        $firebaseLoginUser->delete();
        $oauthAccessTokens->delete();

        // Auth::guard('front_auth')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
