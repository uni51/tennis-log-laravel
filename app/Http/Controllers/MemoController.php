<?php

namespace App\Http\Controllers;

use App\Enums\CategoryType;
use App\Enums\MemoStatusType;
use Illuminate\Http\JsonResponse;

class MemoController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function getCategoryList(): JsonResponse
    {
        return response()->json(CategoryType::asSelectArray());
    }

    /**
     * @return JsonResponse
     */
    public function getStatusList()
    {
        return response()->json(MemoStatusType::asSelectArray());
    }
}
