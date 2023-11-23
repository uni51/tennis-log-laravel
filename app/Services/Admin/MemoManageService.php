<?php
namespace App\Services\Admin;

use App\Http\Resources\Admin\MemoManageResource;
use App\Models\Memo;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MemoManageService
{
    /**
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function list(): AnonymousResourceCollection
    {
        try {
            $users = Memo::orderBy('id')
                    ->paginate(10);
        } catch (Exception $e) {
            throw $e;
        }

        return MemoManageResource::collection($users);
    }
}
