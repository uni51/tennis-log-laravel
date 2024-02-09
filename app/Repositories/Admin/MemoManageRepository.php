<?php
namespace App\Repositories\Admin;

use App\Consts\Pagination;
use App\Models\Memo;
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
}

