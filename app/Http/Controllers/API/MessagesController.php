<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\MessageService;
use Illuminate\Http\JsonResponse;
use App\Services\JWTService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessagesController extends Controller
{
    public function showPublicMessage(MessageService $messageService): JsonResponse
    {
        return response()->json($messageService->getPublicMessage()->toArray());
    }

    public function showAdminMessage(MessageService $messageService): JsonResponse
    {
        return response()->json($messageService->getAdminMessage()->toArray());
    }

    public function showProtectedMessage(JWTService $jwtService, Request $request): JsonResponse
    {
//        $token = $jwtService->extractBearerTokenFromRequest($request);
//        $user = $jwtService->decodeBearerToken($token);
        $user = Auth::guard('api')->user();
        dd($user);
    }

//    public function showProtectedMessage(MessageService $messageService): JsonResponse
//    {
//        return response()->json($messageService->getProtectedMessage()->toArray());
//    }
}
