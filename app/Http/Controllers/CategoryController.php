<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{

    /**
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function list()
    {
        try {
            $categories = Category::all();
        } catch (Exception $e) {
            throw $e;
        }

        return CategoryResource::collection($categories);
    }
}
