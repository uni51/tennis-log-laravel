<?php

namespace App\Http\Controllers\UserAuth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserAuth\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): UserResource
    {
        $request->authenticate();

        $request->session()->regenerate();

        //return response()->noContent();
        return new UserResource(Auth::user());
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
