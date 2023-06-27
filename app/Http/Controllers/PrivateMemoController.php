<?php

namespace App\Http\Controllers;

use App\Services\PrivateMemoService;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class PrivateMemoController extends Controller
{

    /**
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function list(PrivateMemoService $service): AnonymousResourceCollection
    {
        // ログインユーザーのID取得
        $userId = Auth::id();
        if (!$userId) {
            throw new Exception('未ログインです。');
        }
        return $service->listPrivateMemoLinkedToUser($userId);
    }


}
