<?php

namespace App\Http\Controllers;

use App\Services\PublicMemoService;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PublicMemoController extends Controller
{

    /**
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function list(PublicMemoService $service): AnonymousResourceCollection
    {
        return $service->publicListMemo();
    }

    public function listBelongsUser($userId , PublicMemoService $service): AnonymousResourceCollection
    {
        return $service->publicListMemoBelongsUser($userId);
    }
}
