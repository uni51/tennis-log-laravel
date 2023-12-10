<?php
namespace App\Services;

use App\Enums\CategoryType;
use App\Enums\MemoStatusType;
use App\Http\Resources\MemoResource;
use App\Models\Memo;
use App\Models\User;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PublicMemoService
{
    /**
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function publicMemos(): AnonymousResourceCollection
    {
        try {
            $memos = Memo::where('status', MemoStatusType::PUBLISHING)
                        ->paginate(6);
        } catch (Exception $e) {
            throw $e;
        }

        return MemoResource::collection($memos);
    }

    /**
     * @param $nickName
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function publicMemosByNickname($nickName)
    {
        try {
            DB::beginTransaction();

            $user = User::where('nickname', $nickName)->firstOrFail();

            $memos = Memo::where('user_id', $user->id)
                ->where('status', MemoStatusType::PUBLISHING)
                ->paginate(6);

            DB::commit();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            throw $e;
        }

        return MemoResource::collection($memos);
    }

    public function publicMemoDetailsByNickname($nickName, $memoId)
    {
        try {
            DB::beginTransaction();

            $user = User::where('nickname', $nickName)->firstOrFail();

            $memos = Memo::where('user_id', $user->id)
                ->where('status', MemoStatusType::PUBLISHING)
                ->where('id', $memoId)
                ->firstOrFail();

            DB::commit();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            throw $e;
        }

        return MemoResource::make($memos);
    }

    public function publicMemosByCategory($categoryId)
    {
        try {
            $memos = Memo::where('status', MemoStatusType::PUBLISHING)
                ->where('category_id', $categoryId)
                ->paginate(6);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            throw $e;
        }

        return MemoResource::collection($memos);
    }

    public function publicMemoListByNicknameAndCategory($nickName, $categoryId)
    {
        try {
            DB::beginTransaction();

            $user = User::where('nickname', $nickName)->firstOrFail();

            $memos = Memo::where('user_id', $user->id)
                ->where('category_id', $categoryId)
                ->where('status', MemoStatusType::PUBLISHING)
                ->paginate(6);

            DB::commit();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            throw $e;
        }

        return MemoResource::collection($memos);
    }
}
