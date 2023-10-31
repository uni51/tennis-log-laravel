<?php

namespace App\Http\Controllers\Profile;

use App\Enums\PlayFrequencyType;
use App\Http\Controllers\Controller;

class PlayFrequencyController extends Controller
{
    public function playFrequencyList(): \Illuminate\Http\JsonResponse
    {
        return response()->json(PlayFrequencyType::asSelectArray());
    }
}
