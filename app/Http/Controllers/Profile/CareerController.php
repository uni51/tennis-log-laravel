<?php

namespace App\Http\Controllers\Profile;

use App\Enums\Profile\CareerType;
use App\Http\Controllers\Controller;

class CareerController extends Controller
{
    public function careerList(): \Illuminate\Http\JsonResponse
    {
        return response()->json(CareerType::asSelectArray());
    }
}
