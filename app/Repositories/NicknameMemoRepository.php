<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Consts\Pagination;
use App\Enums\MemoStatusType;
use App\Models\Memo;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class NicknameMemoRepository extends BaseMemoRepository
{
    /**
     * @param string $nickname
     * @return LengthAwarePaginator
     */
    public function publicNicknameMemoList(string $nickname): LengthAwarePaginator
    {
        $user = User::where('nickname', $nickname)->firstOrFail();

        return Memo::with(['category:name,id'])
            ->where('user_id', $user->id)
            ->where('status', MemoStatusType::getValue('公開中'))
            ->paginate(Pagination::DEFAULT_PER_PAGE);
    }

    /**
     * @param string $nickname
     * @param int $id
     * @return Builder | Memo
     */
    public function publicNicknameMemoDetail(string $nickname, int $id): Builder | Memo
    {
        $user = User::where('nickname', $nickname)->firstOrFail();

        return Memo::with(['category:name,id'])
            ->where('user_id', $user->id)
            ->where('status', MemoStatusType::getValue('公開中'))
            ->where('id', $id)
            ->firstOrFail();
    }

    /**
     * @param string $nickname
     * @param int $categoryId
     * @return LengthAwarePaginator
     */
    public function publicNicknameMemoListByCategory(string $nickname, int $categoryId): LengthAwarePaginator
    {
        $user = User::where('nickname', $nickname)->firstOrFail();

        return Memo::with(['category:name,id'])
            ->where('user_id', $user->id)
            ->where('category_id', $categoryId)
            ->where('status', MemoStatusType::getValue('公開中'))
            ->paginate(Pagination::DEFAULT_PER_PAGE);
    }

    /**
     * @param string $nickname
     * @param string $tag
     * @return LengthAwarePaginator
     */
    public function publicNicknameMemoListByTag(string $nickname, string $tag): LengthAwarePaginator
    {
        $user = User::where('nickname', $nickname)->firstOrFail();

        return Memo::with(['category:name,id'])
            ->where('user_id', $user->id)
            ->whereHas('tags', function($q) use ($tag) {
                $q->where('normalized', $tag);
            })
            ->where('status', MemoStatusType::getValue('公開中'))
            ->orderBy('updated_at', 'desc')
            ->paginate(Pagination::DEFAULT_PER_PAGE);
    }

    /**
     * @param string $nickname
     * @param int $categoryId
     * @param string $tag
     * @return LengthAwarePaginator
     */
    public function publicNicknameMemoListByCategoryAndTag(string $nickname, int $categoryId, string $tag): LengthAwarePaginator
    {
        $user = User::where('nickname', $nickname)->firstOrFail();

        return Memo::with(['category:name,id'])
            ->where('user_id', $user->id)
            ->where('category_id', $categoryId)
            ->whereHas('tags', function($q) use ($tag) {
                $q->where('normalized', $tag);
            })
            ->where('status', MemoStatusType::getValue('公開中'))
            ->orderBy('updated_at', 'desc')
            ->paginate(Pagination::DEFAULT_PER_PAGE);
    }
}
