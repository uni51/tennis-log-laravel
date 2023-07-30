<?php

namespace App\Http\Controllers\UserAuth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use Kreait\Firebase\Factory;
use function Psy\debug;

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
        
        try {
            $verifiedIdToken = $this->auth->verifyIdToken(json_decode($id_token));
        } catch (FailedToVerifyToken $e) {
            Log::debug($e->getMessage());
            return response()->json([
                'error' => $e->getMessage(),
            ]);
        }

        $uid = $verifiedIdToken->claims()->get('sub');
        $firebase_user = $this->auth->getUser($uid);
        return response()->json([
            'uid' => $uid,
            'name' => $firebase_user->displayName,
        ]);
    }
}
