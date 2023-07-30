<?php

namespace App\Http\Controllers\UserAuth;

use App\Http\Controllers\Controller;

use App\Models\User;
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
//        $id_token = $request->headers->get('authorization');

        Log::debug($id_token);

        try {
            $verifiedIdToken = $this->auth->verifyIdToken(json_decode($id_token));
//            $verifiedIdToken = $this->auth->verifyIdToken($id_token);
        } catch (FailedToVerifyToken $e) {
            Log::debug($e->getMessage());
            return response()->json([
                'error' => $e->getMessage(),
            ]);
        }

        $uid = $verifiedIdToken->claims()->get('sub');
        $firebase_user = $this->auth->getUser($uid);

        $user = User::where('firebase_uid', $uid)->first();

        Log::debug($user);

        if (is_null($user)) {
            $user = User::create([
                'firebase_uid' => $uid,
                'name' => $firebase_user->displayName,
                'nickname' => $firebase_user->displayName,
                'email' => $firebase_user->email,
            ]);
        }

        $user = User::find($user->id);

        $tokenResult = $user->createToken('Personal Access Token');

        Log::debug($tokenResult);
        // トークンの期限
        $expires_at = Carbon::now()->addWeeks(1);
        $user->update(['access_token' => $tokenResult->token->id, 'expires_at' => $expires_at]);

        return response()->json([
            'uid' => $uid,
            'name' => $firebase_user->displayName,
            'token' => $tokenResult->token->id // $tokenResult->accessToken に変更する必要があるかも？
        ]);
    }
}
