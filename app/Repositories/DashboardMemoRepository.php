<?php
namespace App\Repositories;

use App\Consts\Pagination;
use App\Consts\TagConst;
use App\Enums\MemoAdminReviewStatusType;
use App\Enums\MemoChatGptReviewStatusType;
use App\Models\Memo;
use App\Models\Tag;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DashboardMemoRepository extends BaseMemoRepository
{
    /**
     * @param array $validated
     * @return Memo
     * @throws Exception
     */
    public function dashboardMemoCreate(array $validated): Memo
    {
        try {
            DB::beginTransaction();
            $memo = $this->createNewMemo($validated);
            // 配列をコレクションに変換してからeachメソッドを使用
            $this->attachTagsToMemo($memo, $validated['tags']);
            DB::commit();
            return $memo;
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
            'title' => $data['title'],
            'body' => $data['body'],
            'status' => $data['status'],
            'chatgpt_review_status' => $data['chatgpt_review_status'] ?? MemoChatGptReviewStatusType::NOT_REVIEWED,
            'chatgpt_reviewed_at' => $data['chatgpt_reviewed_at'] ?? null,
            'admin_review_status'=> $data['admin_review_status'] ?? MemoAdminReviewStatusType::NOT_REVIEWED,
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

    /**
     * メモに紐づくタグを同期します。
     *
     * @param Memo $memo タグを同期するメモ
     * @param array $tags タグの配列
     * @throws Exception
     */
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

        DB::beginTransaction();
        try {
            // syncメソッドでメモとタグのリレーション（中間テーブル）を更新
            $memo->tags()->sync($tagIds);
            // 不要になったタグを削除する
            // $this->deleteUserUnusedTags($memo->user);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            throw new Exception('タグの同期に失敗しました。');
        }

    }

    /**
     * @param Memo $memo
     * @param array $validated
     * @return Memo
     */
    public function updateMemo(Memo $memo, array $validated): Memo
    {
        $memo->fill($validated);
        $memo->save();

        // 現在のモデルインスタンスをデータベースの最新の状態に同期
        $memo->refresh();

        return $memo;
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
