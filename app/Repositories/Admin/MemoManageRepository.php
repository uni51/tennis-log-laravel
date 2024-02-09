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
            ->paginate(Pagination::DEFAULT_PER_PAGE);
    }
}

