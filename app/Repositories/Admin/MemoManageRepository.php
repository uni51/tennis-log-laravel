<?php
namespace App\Repositories\Admin;

use App\Consts\Pagination;
use App\Models\Memo;
use App\Models\User;
use App\Repositories\BaseMemoRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MemoManageRepository extends BaseMemoRepository
{
    public function getMemoList(): LengthAwarePaginator
    {
        return Memo::with(['category:name,id'])
            ->orderBy('updated_at', 'desc')
            ->paginate(Pagination::ADMIN_DEFAULT_PER_PAGE);
    }

    /**
     * @param int $categoryId
     * @return LengthAwarePaginator
     */
    public function memoListByCategory(int $categoryId): LengthAwarePaginator
    {
        return  Memo::with(['category:name,id'])
            ->where('category_id', $categoryId)
            ->orderBy('updated_at', 'desc')
            ->paginate(Pagination::ADMIN_DEFAULT_PER_PAGE);
    }

    /**
     * @param string $nickname
     * @return LengthAwarePaginator
     */
    public function userMemoListByNickname(string $nickname): LengthAwarePaginator
    {
        $user = User::where('nickname', $nickname)->firstOrFail();

        return Memo::with(['category:name,id'])
            ->where('user_id', $user->id)
            ->paginate(Pagination::ADMIN_DEFAULT_PER_PAGE);
    }

    /**
     * @param string $nickname
     * @param int $categoryId
     * @return LengthAwarePaginator
     */
    public function userMemoListByCategory(string $nickname, int $categoryId): LengthAwarePaginator
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
    public function memoListByTag(string $nickname, string $tag): LengthAwarePaginator
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
    public function memoListByCategoryAndTag(string $nickname, int $categoryId, string $tag): LengthAwarePaginator
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

