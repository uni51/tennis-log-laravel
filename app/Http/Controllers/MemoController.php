<?php

namespace App\Http\Controllers;

use App\Enums\MemoStatusType;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MemoController extends Controller
{

    /**
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function getCategoryList()
    {
        try {
            $categories = Category::all();
        } catch (Exception $e) {
            throw $e;
        }

        return CategoryResource::collection($categories);
    }

    public function getStatusList()
    {
        return response()->json(MemoStatusType::asSelectArray());
    }
}
