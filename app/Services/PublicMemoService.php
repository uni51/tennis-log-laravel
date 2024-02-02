<?php
namespace App\Services;

use App\Enums\MemoStatusType;
use App\Http\Requests\PublicMemoSearchRequest;
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
    public function allList(): AnonymousResourceCollection
    {
        try {
            $memos = Memo::with(['category:name,id'])
                        ->where('status', MemoStatusType::getValue('公開中'))
                        ->orderBy('updated_at', 'desc')
                        ->orderBy('id', 'desc')
                        ->paginate(6);
        } catch (Exception $e) {
            throw $e;
        }

        return MemoResource::collection($memos);
    }


    /**
     * @param int $id
     * @return MemoResource
     */
    public function show(int $id): MemoResource
    {
        $memo = Memo::where('status', MemoStatusType::getValue('公開中'))->findOrFail($id);

        return new MemoResource($memo);
    }

    /**
     * @param string $input_keyword
     * @return AnonymousResourceCollection
     */
    public function search(string $input_keyword): AnonymousResourceCollection
    {
        $query = (new Memo)->newQuery();
        $query->where('status', 1);

        // search title and description for provided strings (space-separated)
        if ($input_keyword) {
            $keywords = explode(' ', $input_keyword);

            $query->where(function($q) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $q->where(function($qq) use ($keyword) {
                        $qq->orWhere('title', 'like', '%'.$keyword.'%')
                            ->orWhere('body', 'like', '%'.$keyword.'%');
                    });
                }
            });
        }

        $memos = $query->with(['category:name,id'])->paginate(6);
        return MemoResource::collection($memos);
    }

    /**
     * @param $nickName
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function userMemoList($nickName)
    {
        try {
            DB::beginTransaction();

            $user = User::where('nickname', $nickName)->firstOrFail();

            $memos = Memo::with(['category:name,id'])
                ->where('user_id', $user->id)
                ->where('status', 1)
                ->paginate(6);

            DB::commit();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            throw $e;
        }

        return MemoResource::collection($memos);
    }

    public function userMemoDetail($nickName, $memoId)
    {
        try {
            DB::beginTransaction();

            $user = User::where('nickname', $nickName)->firstOrFail();

            $memos = Memo::with(['category:name,id'])
                ->where('user_id', $user->id)
                ->where('status', 1)
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

    public function memoListByCategory($categoryId)
    {
        try {
            $memos = Memo::where('category_id', $categoryId)
                ->where('status', 1)
                ->paginate(6);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            throw $e;
        }

        return MemoResource::collection($memos);
    }

    public function userMemoListByCategory($nickName, $categoryId)
    {
        try {
            DB::beginTransaction();

            $user = User::where('nickname', $nickName)->firstOrFail();

            $memos = Memo::with(['category:name,id'])
                ->where('user_id', $user->id)
                ->where('category_id', $categoryId)
                ->where('status', 1)
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
