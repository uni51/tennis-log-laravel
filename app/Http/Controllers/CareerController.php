<?php

namespace App\Http\Controllers;

use App\Enums\CareerType;

class CareerController extends Controller
{
    public function getCareerTypeList()
    {
        return response()->json(CareerType::asSelectArray());
    }
}
