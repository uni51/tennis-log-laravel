<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Consts\Pagination;
use App\Enums\MemoStatusType;
use App\Models\Memo;
use Illuminate\Pagination\LengthAwarePaginator;

class PublicMemoRepository
{

    /**
     * @return LengthAwarePaginator
     */
    public function allPublicList(): LengthAwarePaginator
    {
        return Memo::with(['category:name,id'])
                ->where('status', MemoStatusType::getValue('公開中'))
                ->orderBy('updated_at', 'desc')
                ->orderBy('id', 'desc')
                ->paginate(Pagination::DEFAULT_PER_PAGE);
    }

    /**
     * @param int $id
     * @return Memo|null
     */
    public function publicMemoById(int $id): ?Memo
    {
        return Memo::where('status', MemoStatusType::getValue('公開中'))->findOrFail($id);
    }


    /**
     * @param string $input_keyword
     * @return LengthAwarePaginator
     */
    public function searchPublicMemoList(string $input_keyword): LengthAwarePaginator
    {
        $query = (new Memo)->newQuery();
        $query->where('status', MemoStatusType::getValue('公開中'));

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

        return $query->with(['category:name,id'])->paginate(Pagination::DEFAULT_PER_PAGE);
    }

    /**
     * @param int $categoryId
     * @return LengthAwarePaginator
     */
    public function publicMemoListByCategory(int $categoryId): LengthAwarePaginator
    {
        return Memo::where('category_id', $categoryId)
            ->where('status', MemoStatusType::getValue('公開中'))
            ->paginate(Pagination::DEFAULT_PER_PAGE);
    }
}
