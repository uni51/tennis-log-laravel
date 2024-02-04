<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Consts\Pagination;
use App\Enums\MemoStatusType;
use App\Models\Memo;
use Illuminate\Pagination\LengthAwarePaginator;

class PublicMemoRepository
{

    public function getAllPublicList(): LengthAwarePaginator
    {
        return Memo::with(['category:name,id'])
                ->where('status', MemoStatusType::getValue('公開中'))
                ->orderBy('updated_at', 'desc')
                ->orderBy('id', 'desc')
                ->paginate(Pagination::DEFAULT_PER_PAGE);
    }
}
