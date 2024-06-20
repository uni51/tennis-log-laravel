<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Consts\Pagination;
use App\Enums\MemoStatusType;
use App\Models\Memo;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;

class PublicMemoRepository extends BaseMemoRepository
{
    /**
     * @return LengthAwarePaginator
     */
    public function publicMemoList(): LengthAwarePaginator
    {
        return Memo::with(['category:name,id'])
                ->where('status', MemoStatusType::getValue('公開中'))
                ->orderBy('updated_at', 'desc')
                ->orderBy('id', 'desc')
                ->paginate(Pagination::DEFAULT_PER_PAGE);
    }

//    public function publicMemoListWithRedis($pageName = 'page'): LengthAwarePaginator
//    {
//        $page = Paginator::resolveCurrentPage($pageName);
//        // この場合、ページャの番号に応じてキャッシュする内容が変わるので、キャッシュのキーにページングの情報を含める必要がある
//        return Cache::remember('public-memo-list-'.$page, 30, function () {
//            return Memo::with(['category:name,id'])
//                    ->where('status', MemoStatusType::getValue('公開中'))
//                    ->orderBy('updated_at', 'desc')
//                    ->orderBy('id', 'desc')
//                    ->paginate(Pagination::DEFAULT_PER_PAGE);
//        });
//    }

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
                            ->orWhere('body', 'like', '%'.$keyword.'%')
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
            ->paginate(Pagination::DEFAULT_PER_PAGE);
    }

    /**
     * @param int $categoryId
     * @return LengthAwarePaginator
     */
    public function publicMemoListByCategory(int $categoryId): LengthAwarePaginator
    {
        return Memo::where('category_id', $categoryId)
            ->where('status', MemoStatusType::getValue('公開中'))
            ->orderBy('updated_at', 'desc')
            ->paginate(Pagination::DEFAULT_PER_PAGE);
    }

    /**
     * @param string $tag
     * @return LengthAwarePaginator
     */
    public function publicMemoListByTag(string $tag): LengthAwarePaginator
    {
        return Memo::with(['category:name,id'])
            ->where('status', MemoStatusType::getValue('公開中'))
            ->whereHas('tags', function($q) use ($tag) {
                $q->where('name', $tag);
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(Pagination::DEFAULT_PER_PAGE);
    }

    /**
     * @param int $categoryId
     * @param string $tag
     * @return LengthAwarePaginator
     */
    public function publicMemoListByCategoryAndTag(int $categoryId, string $tag): LengthAwarePaginator
    {
        return Memo::with(['category:name,id'])
            ->where('status', MemoStatusType::getValue('公開中'))
            ->where('category_id', $categoryId)
            ->whereHas('tags', function($q) use ($tag) {
                $q->where('name', $tag);
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(Pagination::DEFAULT_PER_PAGE);
    }
}
