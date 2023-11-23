<?php

namespace App\Http\Controllers\Profile;

use App\Enums\Profile\GenderType;
use App\Http\Controllers\Controller;

class GenderController extends Controller
{
    public function genderList(): \Illuminate\Http\JsonResponse
    {
        return response()->json(GenderType::asSelectArray());
    }
}
