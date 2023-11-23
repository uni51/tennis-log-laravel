<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\UserManageService;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Class UserManageController
 * Admin用ユーザー管理コントローラー
 *
 * @package App\Http\Controllers
 */
class UserManageController extends Controller
{
    /**
     * @param UserManageService $service
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function list(UserManageService $service): AnonymousResourceCollection
    {
        // $adminId = Auth::guard('admin')->id();
        // Log::debug($adminId);
        // if (!$adminId) {
        //    throw new Exception('未ログインです。');
        // }
        return $service->list();
    }
}
