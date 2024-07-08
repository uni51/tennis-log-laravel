<?php

namespace App\Http\Controllers;

use App\Enums\MemoStatusType;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Memo;
use Exception;
use Illuminate\Http\JsonResponse;
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

    /**
     * 指定したメモのお気に入り数を取得する。
     *
     * @param int $id
     * @return JsonResponse
     */
    public function countFavorites(int $id): JsonResponse
    {
        $memo = Memo::withCount('bookmarkedBy')->findOrFail($id);

        return response()->json([
            'memo_id' => $memo->id,
            'favorites_count' => $memo->bookmarked_by_count
        ]);
    }
}
