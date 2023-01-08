<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Trait\UserInfo;
use App\Models\Memo;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class MemoController extends Controller
{
    use UserInfo;

    /**
     * メモの全件取得
     * @return AnonymousResourceCollection
     */
    public function fetch(Request $request): AnonymousResourceCollection
    {
        dd($request->userId);

        $id = $this->getUserId();

        dd($id);

//        try {
//            $memos = Memo::where('auth0_id', $)
//                ->get();
//        } catch (Exception $e) {
//            throw $e;
//        }
    }
}
