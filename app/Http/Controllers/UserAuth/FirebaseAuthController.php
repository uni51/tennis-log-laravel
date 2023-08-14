<?php

namespace App\Http\Controllers\UserAuth;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
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
        // $id_token = $request->headers->get('authorization');
        // $token = trim(str_replace('Bearer', '', $id_token));

        Log::debug('Login idToken:'.$id_token);

        try {
            // $verifiedIdToken = $this->auth->verifyIdToken(json_decode($id_token));
            $verifiedIdToken = $this->auth->verifyIdToken($id_token);
        } catch (FailedToVerifyToken $e) {
            Log::debug($e->getMessage());
            return response()->json([
                'error' => $e->getMessage(),
            ]);
        }

        $firebaseUid = $verifiedIdToken->claims()->get('sub');
        $firebaseUser = $this->auth->getUser($firebaseUid);

        $user = User::where('firebase_uid', $firebaseUid)->first();

//        Log::debug('Login User:'. var_export($firebaseUser));

        if (is_null($user)) {
            $user = User::create([
                'firebase_uid' => $firebaseUid,
                'name' => $firebaseUser->displayName,
                'nickname' => $firebaseUser->displayName,
                'email' => $firebaseUser->email,
            ]);
        }

        $user = User::find($user->id);

        $tokenResult = $user->createToken('Personal Access Token');

        Log::debug('Login accessToken:'.$tokenResult->accessToken);

        // トークンの期限
        $expires_at = Carbon::now()->addWeeks(1);
        $user->update(['access_token' => $tokenResult->accessToken, 'expires_at' => $expires_at]);

        // Auth::guard('front_auth')->login($user);

        return response()->json([
            'uid' => $firebaseUid,
            // ネームが設定されないことは基本的にはない筈だが、念の為の処理
            'name' => $firebaseUser->displayName ?? $firebaseUser->email,
            'token' => $tokenResult->accessToken
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function logout(Request $request): Response
    {
        auth('front_api')->user()->tokens()->delete();

        $id_token = $request->headers->get('authorization');
        $token = trim(str_replace('Bearer', '', $id_token));

        $user = User::where('access_token', $token)->first();
        $user->access_token = null;
        $user->save();

        // Auth::guard('front_auth')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
