<?php

namespace App\Http\Trait;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait  UserInfo {

    private function getUserId() {
        $auth0User = Auth::guard('api')->user();
        return User::where('auth0_id', $auth0User->sub)->first()->id;
    }
}
