<?php

namespace App\Http\Controllers\Profile;

use App\Enums\AgeRangeType;
use App\Http\Controllers\Controller;

class AgeRangeController extends Controller
{
    public function ageLangeList(): \Illuminate\Http\JsonResponse
    {
        return response()->json(AgeRangeType::asSelectArray());
    }
}
