<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Consts\Pagination;
use App\Enums\MemoStatusType;
use App\Models\Memo;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class NicknameMemoRepository
{

    /**
     * @param string $nickname
     * @return LengthAwarePaginator
     */
    public function userMemoList(string $nickname): LengthAwarePaginator
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
    public function userMemoDetail(string $nickname, int $id): Builder | Memo
    {
        $user = User::where('nickname', $nickname)->firstOrFail();

        return Memo::with(['category:name,id'])
            ->where('user_id', $user->id)
            ->where('status', MemoStatusType::getValue('公開中'))
            ->where('id', $id)
            ->firstOrFail();
    }


    /**
     * @param string $nickName
     * @param int $categoryId
     * @return LengthAwarePaginator
     */
    public function userMemoListByCategory(string $nickName, int $categoryId): LengthAwarePaginator
    {
        $user = User::where('nickname', $nickName)->firstOrFail();

        return Memo::with(['category:name,id'])
            ->where('user_id', $user->id)
            ->where('category_id', $categoryId)
            ->where('status', MemoStatusType::getValue('公開中'))
            ->paginate(Pagination::DEFAULT_PER_PAGE);
    }
}
