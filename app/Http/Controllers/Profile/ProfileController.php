<?php

namespace App\Http\Controllers\Profile;

use App\Enums\Profile\PlayFrequencyType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\ProfileCreateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function createProfile(ProfileCreateRequest $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $validated = $request->validated();
        Log::debug($validated);
    }
}
