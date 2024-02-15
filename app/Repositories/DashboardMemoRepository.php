<?php
namespace App\Repositories;

use App\Consts\Pagination;
use App\Models\Memo;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DashboardMemoRepository extends BaseMemoRepository
{
//    /**
//     * @param int $id
//     * @return Memo|null
//     */
//    public function getMemoById(int $id): ?Memo
//    {
//        $memo = Memo::find($id);
//
//        if (!$memo) {
//            abort(404, '指定されたIDのメモが見つかりません。');
//        }
//
//        return $memo;
//    }

    /**
     * @param array $validated
     * @return void
     * @throws Exception
     */
    public function dashboardMemoCreate(array $validated): void
    {
        try {
            DB::beginTransaction();
            $memo = $this->createNewMemo($validated);
            $this->attachTagsToMemo($memo, $validated['tags'] ?? []);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            throw new Exception('メモの登録に失敗しました。');
        }
    }

    /**
     * 新しいメモを作成して返します。
     *
     * @param array $data メモのデータ
     * @return Memo 作成されたメモ
     */
    private function createNewMemo(array $data): Memo
    {
        return Memo::create([
            'user_id' => Auth::id(),
            'category_id' => $data['category_id'],
            'status' => $data['status_id'],
            'title' => $data['title'],
            'body' => $data['body'],
        ]);
    }

    /**
     * メモにタグを紐付けます。
     *
     * @param Memo $memo タグを紐付けるメモ
     * @param array $tags タグの配列
     */
    private function attachTagsToMemo(Memo $memo, array $tags): void
    {
        if (!empty($tags)) {
            $memo->tag($tags);
        }
    }

    /**
     * @param Memo $memo
     * @param array $validated
     * @return bool
     */
    public function updateMemo(Memo $memo, array $validated): bool
    {
//        $memo->title = $validated['title'];
//        $memo->body = $validated['body'];
//        $memo->category_id = $validated['category_id'];
//        $memo->status = $validated['status_id'];
        $memo->fill($validated);
        return $memo->save();
    }

    /**
     * @param int $authUserId
     * @return LengthAwarePaginator
     */
    public function dashboardMemoListByAuthUser(int $authUserId): LengthAwarePaginator
    {
        return Memo::with(['category:name,id'])
                ->where('user_id', $authUserId)
                ->orderBy('updated_at', 'desc')
                ->paginate(Pagination::DEFAULT_PER_PAGE);
    }

    /**
     * @param int $authUserId
     * @param int $categoryId
     * @return LengthAwarePaginator
     */
    public function dashboardMemoListByCategory(int $authUserId, int $categoryId): LengthAwarePaginator
    {
        return  Memo::with(['category:name,id'])
                    ->where('user_id', $authUserId)
                    ->where('category_id', $categoryId)
                    ->orderBy('updated_at', 'desc')
                    ->paginate(Pagination::DEFAULT_PER_PAGE);
    }

    /**
     * @param int $authUserId
     * @param string $keyword
     * @return LengthAwarePaginator
     */
    public function dashboardMemoSearch(int $authUserId, string $keyword): LengthAwarePaginator
    {
        $query = (new Memo)->newQuery();
        $query->where('user_id', $authUserId);

        // search title and description for provided strings (space-separated)
        if ($keyword) {
            $keywords = explode(' ', $keyword);

            $query->where(function ($q) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $q->where(function ($qq) use ($keyword) {
                        $qq->orWhere('title', 'like', '%' . $keyword . '%')
                            ->orWhere('body', 'like', '%' . $keyword . '%');
                    });
                }
            });
        }

       return $query->with(['category:name,id'])
                ->orderBy('updated_at', 'desc')
                ->paginate(Pagination::DEFAULT_PER_PAGE);
    }

    /**
     * @param int $authUserId
     * @param int $status
     * @return LengthAwarePaginator
     */
    public function dashboardMemoListByStatus(int $authUserId, int $status): LengthAwarePaginator
    {
        return Memo::with(['category:name,id'])
                ->where('user_id', $authUserId)
                ->where('status', $status)
                ->orderBy('updated_at', 'desc')
                ->paginate(Pagination::DEFAULT_PER_PAGE);
    }

    /**
     * @param int $authUserId
     * @param string $tag
     * @return LengthAwarePaginator
     */
    public function dashboardMemoListByTag(int $authUserId, string $tag): LengthAwarePaginator
    {
        return Memo::with(['category:name,id'])
            ->where('user_id', $authUserId)
            ->whereHas('tags', function($q) use ($tag) {
                $q->where('normalized', $tag);
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(Pagination::DEFAULT_PER_PAGE);
    }

    /**
     * @param int $authUserId
     * @param int $categoryId
     * @param string $tag
     * @return LengthAwarePaginator
     */
    public function memoListByCategoryAndTag(int $authUserId, int $categoryId, string $tag): LengthAwarePaginator
    {
        return Memo::with(['category:name,id'])
            ->where('user_id', $authUserId)
            ->where('category_id', $categoryId)
            ->whereHas('tags', function($q) use ($tag) {
                $q->where('normalized', $tag);
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(Pagination::DEFAULT_PER_PAGE);
    }
}
