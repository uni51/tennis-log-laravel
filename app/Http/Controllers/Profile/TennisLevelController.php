<?php

namespace App\Http\Controllers\Profile;

use App\Enums\Profile\TennisLevelType;
use App\Http\Controllers\Controller;

class TennisLevelController extends Controller
{
    public function tennisLevelList(): \Illuminate\Http\JsonResponse
    {
        return response()->json(TennisLevelType::asSelectArray());
    }
}
