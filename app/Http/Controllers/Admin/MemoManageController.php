<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\MemoManageService;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Class MemoManageController
 * Admin用メモ管理コントローラー
 *
 * @package App\Http\Controllers
 */
class MemoManageController extends Controller
{
    /**
     * @param MemoManageService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function list(MemoManageService $service): AnonymousResourceCollection
    {
        return $service->list();
    }
}