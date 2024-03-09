<?php
namespace App\Repositories\Admin;

use App\Consts\Pagination;
use App\Enums\MemoStatusType;
use App\Models\Memo;
use App\Models\User;
use App\Repositories\BaseMemoRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MemoManageRepository extends BaseMemoRepository
{
    public function adminMemoList(): LengthAwarePaginator
    {
        return Memo::with(['category:name,id'])
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
                $q->where('normalized', $tag);
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
                $q->where('normalized', $tag);
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
                $q->where('normalized', $tag);
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
                $q->where('normalized', $tag);
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(Pagination::ADMIN_DEFAULT_PER_PAGE);
    }
}

