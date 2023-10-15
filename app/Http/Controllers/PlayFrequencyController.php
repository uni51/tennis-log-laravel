<?php

namespace App\Http\Controllers;

use App\Enums\CareerType;
use App\Enums\PlayFrequencyType;

class PlayFrequencyController extends Controller
{
    public function getPlayFrequencyList()
    {
        return response()->json(PlayFrequencyType::asSelectArray());
    }
}
