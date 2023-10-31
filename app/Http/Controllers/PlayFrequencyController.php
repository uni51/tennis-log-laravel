<?php

namespace App\Http\Controllers;

use App\Enums\CareerType;
use App\Enums\PlayFrequencyType;

class PlayFrequencyController extends Controller
{
    public function playFrequencyList(): \Illuminate\Http\JsonResponse
    {
        return response()->json(PlayFrequencyType::asSelectArray());
    }
}
