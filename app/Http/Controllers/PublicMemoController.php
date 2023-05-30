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
    public function allList(PublicMemoService $service): AnonymousResourceCollection
    {
        return $service->allList();
    }

    /**
     * @param PublicMemoService $service
     * @param $nickName
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function userMemoList(PublicMemoService $service, $nickName)
    {
        return $service->userMemoList($nickName);
    }

    public function userMemoDetail(PublicMemoService $service, $nickName, $memoId)
    {
        return $service->userMemoDetail($nickName, $memoId);
    }
}
