<?php
namespace App\Repositories\Admin;

use App\Consts\Pagination;
use App\Consts\TagConst;
use App\Enums\MemoAdminReviewStatusType;
use App\Models\Memo;
use App\Models\Tag;
use App\Models\User;
use App\Repositories\BaseMemoRepository;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MemoManageRepository extends BaseMemoRepository
{
    public function adminMemoList(): LengthAwarePaginator
    {
        return Memo::with(['category:name,id'])
            ->orderBy('updated_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(Pagination::ADMIN_DEFAULT_PER_PAGE);
    }

    public function adminMemoWaitingReviewList(): LengthAwarePaginator
    {
        return Memo::with(['category:name,id'])
            ->where('admin_review_status', MemoAdminReviewStatusType::REVIEW_REQUIRED)
            ->orderBy('updated_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(Pagination::ADMIN_DEFAULT_PER_PAGE);
    }

    public function adminMemoWaitingFixList(): LengthAwarePaginator
    {
        return Memo::with(['category:name,id'])
            ->where('admin_review_status', MemoAdminReviewStatusType::FIX_REQUIRED)
            ->orderBy('updated_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(Pagination::ADMIN_DEFAULT_PER_PAGE);
    }

    /**
     * @param string $keyword
     * @return LengthAwarePaginator
     */
    public function adminMemoSearch(string $keyword): LengthAwarePaginator
    {
        $query = (new Memo)->newQuery();

        // search title and description for provided strings (space-separated)
        if ($keyword) {
            $keywords = explode(' ', $keyword);

            $query->where(function ($q) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $q->where(function ($qq) use ($keyword) {
                        $qq->orWhere('title', 'like', '%' . $keyword . '%')
                            ->orWhere('body', 'like', '%' . $keyword . '%')
                            // ニックネームでの検索を追加
                            ->orWhereHas('user', function($q) use ($keyword) {
                                $q->where('nickname', 'like', '%'.$keyword.'%');
                            });
                    });
                }
            });
        }

        return $query->with(['category:name,id'])
            ->orderBy('updated_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(Pagination::ADMIN_DEFAULT_PER_PAGE);
    }

    /**
     * @param int $categoryId
     * @return LengthAwarePaginator
     */
    public function adminMemoListByCategory(int $categoryId): LengthAwarePaginator
    {
        return  Memo::with(['category:name,id'])
            ->where('category_id', $categoryId)
            ->orderBy('updated_at', 'desc')
            ->paginate(Pagination::ADMIN_DEFAULT_PER_PAGE);
    }

    /**
     * @param string $tag
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function adminMemoListByTag(string $tag): LengthAwarePaginator
    {
        return Memo::with(['category:name,id'])
            ->whereHas('tags', function($q) use ($tag) {
                $q->where('name', $tag);
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(Pagination::ADMIN_DEFAULT_PER_PAGE);
    }

    /**
     * @param int $categoryId
     * @param string $tag
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function adminMemoListByCategoryAndTag(int $categoryId, string $tag): LengthAwarePaginator
    {
        return Memo::with(['category:name,id'])
            ->where('category_id', $categoryId)
            ->whereHas('tags', function($q) use ($tag) {
                $q->where('name', $tag);
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(Pagination::ADMIN_DEFAULT_PER_PAGE);
    }

    /**
     * @param string $nickname
     * @return LengthAwarePaginator
     */
    public function adminNicknameMemoList(string $nickname): LengthAwarePaginator
    {
        $user = User::where('nickname', $nickname)->firstOrFail();

        return Memo::with(['category:name,id'])
            ->where('user_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(Pagination::ADMIN_DEFAULT_PER_PAGE);
    }

    /**
     * @param string $nickname
     * @param int $categoryId
     * @return LengthAwarePaginator
     */
    public function adminNicknameMemoListByCategory(string $nickname, int $categoryId): LengthAwarePaginator
    {
        $user = User::where('nickname', $nickname)->firstOrFail();

        return Memo::with(['category:name,id'])
            ->where('user_id', $user->id)
            ->where('category_id', $categoryId)
            ->paginate(Pagination::ADMIN_DEFAULT_PER_PAGE);
    }

    /**
     * @param string $nickname
     * @param string $tag
     * @return LengthAwarePaginator
     */
    public function adminNicknameMemoListByTag(string $nickname, string $tag): LengthAwarePaginator
    {
        $user = User::where('nickname', $nickname)->firstOrFail();

        return Memo::with(['category:name,id'])
            ->where('user_id', $user->id)
            ->whereHas('tags', function($q) use ($tag) {
                $q->where('name', $tag);
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(Pagination::ADMIN_DEFAULT_PER_PAGE);
    }

    /**
     * @param string $nickname
     * @param int $categoryId
     * @param string $tag
     * @return LengthAwarePaginator
     */
    public function adminNicknameMemoListByCategoryAndTag(string $nickname, int $categoryId, string $tag): LengthAwarePaginator
    {
        $user = User::where('nickname', $nickname)->firstOrFail();

        return Memo::with(['category:name,id'])
            ->where('user_id', $user->id)
            ->where('category_id', $categoryId)
            ->whereHas('tags', function($q) use ($tag) {
                $q->where('name', $tag);
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(Pagination::ADMIN_DEFAULT_PER_PAGE);
    }

    /**
     * @param Memo $memo
     * @return bool
     */
    public function adminMemoDestroy(Memo $memo): bool
    {
        DB::beginTransaction();
        try {
            // DeletedMemoテーブルにデータを移動
            DB::table('deleted_memos')->insert([
                'force_deleted' => true,
                'memo_id'       => $memo->id,
                'user_id'       => $memo->user_id,
                'category_id'   => $memo->category_id,
                'title'         => $memo->title,
                'body'          => $memo->body,
                'status'        => $memo->status,
                'chatgpt_review_status' => $memo->chatgpt_review_status,
                'chatgpt_reviewed_at'   => $memo->chatgpt_reviewed_at,
                'admin_review_status'   => $memo->admin_review_status,
                'admin_reviewed_at'     => $memo->admin_reviewed_at,
                'status_at_review'      => $memo->status_at_review,
                'times_notified_to_fix' => $memo->times_notified_to_fix,
                'times_attempt_to_fix' => $memo->times_attempt_to_fix,
                'approved_at' => $memo->approved_at,
                'memo_created_at' => $memo->created_at,
                'memo_updated_at' => $memo->updated_at,
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);

            // memo_tag テーブルから該当メモに関連するタグのレコードを取得し、deleted_memo_tag へ移動
            $this->archiveAndDetachMemoTags($memo);
            // Tagテーブルから使用されていないタグを削除
            $this->deleteUnusedTagsBelongsToUser($memo->user);
            // メモ自体を削除
            $memo->delete();
            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return false;
        }
    }
}

