<?php

namespace App\Http\Controllers\Profile;

use App\Enums\Profile\DominantHandType;
use App\Http\Controllers\Controller;

class DominantHandController extends Controller
{
    public function dominantHandList(): \Illuminate\Http\JsonResponse
    {
        return response()->json(DominantHandType::asSelectArray());
    }
}
