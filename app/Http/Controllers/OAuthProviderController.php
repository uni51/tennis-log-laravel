<?php

namespace App\Http\Controllers;

use App\Models\OAuthProvider;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;

class OAuthProviderController extends Controller
{
    const GITHUB = 'github';
    const GOOGLE = 'google';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(string $provider)
    {
        return Socialite::driver($provider)->redirect();
    }


    public function store(string $provider)
    {
        $socialite = Socialite::driver($provider)->stateless()->user();

        $user = User::firstOrCreate([
            'email' => $socialite->getEmail(),
        ], [
            'name' => $socialite->getName(),
            //'name' => $socialite->getNickname(),
        ]);

        $user->providers()->updateOrCreate([
            'provider' => self::GOOGLE,
            'provider_id' => $socialite->getId(),
        ]);

        Auth::login($user);

        return redirect(config('app.frontend_url'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OAuthProvider  $oAuthProvider
     * @return \Illuminate\Http\Response
     */
    public function show(OAuthProvider $oAuthProvider)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OAuthProvider  $oAuthProvider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OAuthProvider $oAuthProvider)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OAuthProvider  $oAuthProvider
     * @return \Illuminate\Http\Response
     */
    public function destroy(OAuthProvider $oAuthProvider)
    {
        //
    }
}
