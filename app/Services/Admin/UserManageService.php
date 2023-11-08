<?php
namespace App\Services\Admin;

use App\Http\Resources\Admin\UserManageResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserManageService
{
    /**
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function list(): AnonymousResourceCollection
    {
        try {
            $users = User::orderBy('id')
                    ->paginate(10);
        } catch (Exception $e) {
            throw $e;
        }

        return UserManageResource::collection($users);
    }
}
