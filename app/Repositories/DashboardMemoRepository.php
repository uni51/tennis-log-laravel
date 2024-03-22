<?php
namespace App\Repositories;

use App\Consts\Pagination;
use App\Consts\TagConst;
use App\Models\Memo;
use App\Models\Tag;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DashboardMemoRepository extends BaseMemoRepository
{
//    /**
//     * @param int $id
//     * @return Memo|null
//     */
//    public function getMemoById(int $id): ?Memo
//    {
//        $memo = Memo::find($id);
//
//        if (!$memo) {
//            abort(404, '指定されたIDのメモが見つかりません。');
//        }
//
//        return $memo;
//    }

    /**
     * @param array $validated
     * @return void
     * @throws Exception
     */
    public function dashboardMemoCreate(array $validated): void
    {
        try {
            DB::beginTransaction();
            $memo = $this->createNewMemo($validated);
            // 配列をコレクションに変換してからeachメソッドを使用
            $this->attachTagsToMemo($memo, $validated['tags']);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            throw new Exception('メモの登録に失敗しました。');
        }
    }

    /**
     * 新しいメモを作成して返します。
     *
     * @param array $data メモのデータ
     * @return Memo 作成されたメモ
     */
    private function createNewMemo(array $data): Memo
    {
        return Memo::create([
            'user_id' => Auth::id(),
            'category_id' => $data['category_id'],
            'status' => $data['status_id'],
            'title' => $data['title'],
            'body' => $data['body'],
        ]);
    }

    /**
     * メモにタグを紐付けます。
     *
     * @param Memo $memo タグを紐付けるメモ
     * @param array $tags タグの配列
     */
    private function attachTagsToMemo(Memo $memo, array $tags): void
    {
        // 配列をコレクションに変換してからeachメソッドを使用
        collect($tags)->each(function ($tagName) use ($memo) {
            // タグ名の正規化
            $normalizedTagName = $this->normalizeTagName($tagName);

            // 現在のユーザーまたはadminが作成したタグを検索
            $tag = Tag::where('name', $normalizedTagName,)
                    ->whereIn('created_by', [Auth::id(), TagConst::ADMIN_ID]) // Auth::id() または admin の ID
                    ->first();

            // タグが存在しなければ、新しく作成（admin以外の場合）
            if (!$tag && Auth::id() !== TagConst::ADMIN_ID) {
                $tag = Tag::create([
                    'name' => $normalizedTagName,
                    'created_by' => Auth::id(),
                ]);
            }

            if ($tag) {
                $memo->tags()->attach($tag);
            }
        });
    }
    public function syncTagsToMemo(Memo $memo, array $tags): void
    {
        // タグ名からタグのIDを取得し、存在しないものは新規作成
        $tagIds = collect($tags)->map(function ($name) {
            $normalizedTagName = $this->normalizeTagName($name); // タグ名の正規化
            $tag = Tag::firstOrCreate([
                'name' => $normalizedTagName,
                'created_by' => Auth::id(),
            ]);
            return $tag->id;
        })->all();

        // syncメソッドでメモとタグのリレーションを更新
        $memo->tags()->sync($tagIds);

        // ここで不要になったタグを削除するロジックを追加する
        // 注意: このロジックはアプリケーションの要件に応じて調整する必要があります
        $this->deleteUnusedTags();
    }

    public function deleteUnusedTags(): void
    {
        // まず、使用されていない（他のメモに紐付いていない）、現在のユーザーによって作成された
        // かつ、Adminユーザーによって作成されていないタグを検索します。
        $unusedTagIds = Tag::whereDoesntHave('memos') // memosリレーションを持たないタグを選択
        ->where('created_by', Auth::id()) // 現在のユーザーによって作成された
        ->where('created_by', '!=', TagConst::ADMIN_ID) // Adminによって作成されていない
        ->pluck('id'); // 不要なタグのIDを取得

        // 条件に一致するタグを削除します。
        // pluck('id')により取得したIDリストを使用してdelete()を呼び出すことで、対象となるタグを一括で削除する
        if ($unusedTagIds->isNotEmpty()) {
            Tag::whereIn('id', $unusedTagIds)->delete();
        }
    }

    /**
     * @param Memo $memo
     * @param array $validated
     * @return bool
     */
    public function updateMemo(Memo $memo, array $validated): bool
    {
        $memo->fill($validated);
        return $memo->save();
    }

    /**
     * @param int $authUserId
     * @return LengthAwarePaginator
     */
    public function dashboardMemoListByAuthUser(int $authUserId): LengthAwarePaginator
    {
        return Memo::with(['category:name,id'])
                ->where('user_id', $authUserId)
                ->orderBy('updated_at', 'desc')
                ->paginate(Pagination::DEFAULT_PER_PAGE);
    }

    /**
     * @param int $authUserId
     * @param int $categoryId
     * @return LengthAwarePaginator
     */
    public function dashboardMemoListByCategory(int $authUserId, int $categoryId): LengthAwarePaginator
    {
        return  Memo::with(['category:name,id'])
                    ->where('user_id', $authUserId)
                    ->where('category_id', $categoryId)
                    ->orderBy('updated_at', 'desc')
                    ->paginate(Pagination::DEFAULT_PER_PAGE);
    }

    /**
     * @param int $authUserId
     * @param string $keyword
     * @return LengthAwarePaginator
     */
    public function dashboardMemoSearch(int $authUserId, string $keyword): LengthAwarePaginator
    {
        $query = (new Memo)->newQuery();
        $query->where('user_id', $authUserId);

        // search title and description for provided strings (space-separated)
        if ($keyword) {
            $keywords = explode(' ', $keyword);

            $query->where(function ($q) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $q->where(function ($qq) use ($keyword) {
                        $qq->orWhere('title', 'like', '%' . $keyword . '%')
                            ->orWhere('body', 'like', '%' . $keyword . '%');
                    });
                }
            });
        }

       return $query->with(['category:name,id'])
                ->orderBy('updated_at', 'desc')
                ->paginate(Pagination::DEFAULT_PER_PAGE);
    }

    /**
     * @param int $authUserId
     * @param int $status
     * @return LengthAwarePaginator
     */
    public function dashboardMemoListByStatus(int $authUserId, int $status): LengthAwarePaginator
    {
        return Memo::with(['category:name,id'])
                ->where('user_id', $authUserId)
                ->where('status', $status)
                ->orderBy('updated_at', 'desc')
                ->paginate(Pagination::DEFAULT_PER_PAGE);
    }

    /**
     * @param int $authUserId
     * @param string $tag
     * @return LengthAwarePaginator
     */
    public function dashboardMemoListByTag(int $authUserId, string $tag): LengthAwarePaginator
    {
        return Memo::with(['category:name,id'])
            ->where('user_id', $authUserId)
            ->whereHas('tags', function($q) use ($tag) {
                $q->where('name', $tag);
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(Pagination::DEFAULT_PER_PAGE);
    }

    /**
     * @param int $authUserId
     * @param int $categoryId
     * @param string $tag
     * @return LengthAwarePaginator
     */
    public function memoListByCategoryAndTag(int $authUserId, int $categoryId, string $tag): LengthAwarePaginator
    {
        return Memo::with(['category:name,id'])
            ->where('user_id', $authUserId)
            ->where('category_id', $categoryId)
            ->whereHas('tags', function($q) use ($tag) {
                $q->where('name', $tag);
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(Pagination::DEFAULT_PER_PAGE);
    }
}
