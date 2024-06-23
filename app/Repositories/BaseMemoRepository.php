<?php

namespace App\Repositories;

use App\Consts\TagConst;
use App\Models\DeletedMemo;
use App\Models\DeletedMemoTag;
use App\Models\Memo;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BaseMemoRepository
{
    /**
     * @param int $id
     * @return Memo|null
     */
    public function getMemoById(int $id): ?Memo
    {
        $memo = Memo::find($id);

        if (!$memo) {
            abort(404, '指定されたメモが見つかりません。');
        }

        return $memo;
    }

    /**
     * タグ名を正規化します。
     *
     * @param string $tagName タグ名
     * @return string 正規化されたタグ名
     */
    protected function normalizeTagName(string $tagName): string
    {
        // 'a'は全角英数字を半角に変換することを意味し、's'は全角スペースを半角スペースに変換することを意味する
        return mb_convert_kana(strtolower($tagName), 'as', 'UTF-8');
    }

    public function archiveMemo(Memo $memo, bool $isForceDeleted = false): void
    {
        DeletedMemo::create([
            'memo_id' => $memo->id,
            'user_id' => $memo->user_id,
            'category_id' => $memo->category_id,
            'title' => $memo->title,
            'body' => $memo->body,
            'status' => $memo->status,
            'chatgpt_review_status' => $memo->chatgpt_review_status,
            'chatgpt_reviewed_at' => $memo->chatgpt_reviewed_at,
            'admin_review_status' => $memo->admin_review_status,
            'admin_reviewed_at' => $memo->admin_reviewed_at,
            'status_at_review' => $memo->status_at_review,
            'times_notified_to_fix' => $memo->times_notified_to_fix,
            'times_attempt_to_fix' => $memo->times_attempt_to_fix,
            'approved_by' => $memo->approved_by,
            'approved_at' => $memo->approved_at,
            'memo_created_at' => $memo->created_at,
            'memo_updated_at' => $memo->updated_at,
            'is_force_deleted' => (bool)$isForceDeleted,
        ]);
    }

    /**
     * memo_tagの中間テーブルに関連付けられたタグをアーカイブして、関連を削除する。
     */
    public function archiveAndDetachMemoTags(Memo $memo, bool $isForceDeleted = false): void
    {
        DB::beginTransaction();
        try {
            // タグを取得して、ピボットテーブルのcreated_atとupdated_atも含める
            $memoTags = $memo->tags()->withPivot('created_at', 'updated_at')->get();

            // deleted_memo_tagへのバルクインサートデータを準備
            $deletedTagsData = $memoTags->map(function ($tag) use ($memo, $isForceDeleted ) {
                return [
                    'memo_id' => $memo->id,
                    'tag_id' => $tag->id,
                    'memo_tag_created_at' => $tag->pivot->created_at,
                    'memo_tag_updated_at' => $tag->pivot->updated_at,
                    'created_at' => now()->format('Y-m-d H:i:s'),
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                    'is_force_deleted' => (bool)$isForceDeleted,
                ];
            })->toArray();

            // バルクインサートでdeleted_memo_tagに追加
            if (!empty($deletedTagsData)) {
                DB::table('deleted_memo_tag')->insert($deletedTagsData);
            }

            // memo_tag中間テーブルから関連を削除
            $memo->tags()->detach($memoTags->pluck('id')->toArray());

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // エラー処理をここで行う
            throw $e;
        }
    }

    public function archiveAndDeleteUserUnusedTags(User $user, bool $isForceDeleted = false): void
    {
        // 使用されていないタグを取得
        $unusedTags = Tag::whereDoesntHave('memos')
            ->where('created_by', $user->id)
            ->where('created_by', '!=', TagConst::ADMIN_ID)
            ->get(['id', 'name', 'created_by', 'created_at', 'updated_at'])->toArray();

        DB::beginTransaction();
        try {
            // deleted_tags へのバルクインサートデータを準備
            $deletedTags = array_map(function ($unusedTag) use ($isForceDeleted) {
                return [
                    'tag_id' => $unusedTag['id'],
                    'name' => $unusedTag['name'],
                    'created_by' => $unusedTag['created_by'],
                    'tag_created_at' => Carbon::parse($unusedTag['created_at'])->format('Y-m-d H:i:s'),
                    'tag_updated_at' => Carbon::parse($unusedTag['updated_at'])->format('Y-m-d H:i:s'),
                    'is_force_deleted' => (bool)$isForceDeleted,
                    'created_at' => now()->format('Y-m-d H:i:s'),
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ];
            }, $unusedTags);

            // バルクインサートでdeleted_tagsに追加
            DB::table('deleted_tags')->insert($deletedTags);

            // 元のタグを削除
            Tag::whereIn('id', array_column($unusedTags, 'id'))->delete();

            // トランザクションをコミット
            DB::commit();
        } catch (\Exception $e) {
            // エラーが発生した場合、ロールバック
            DB::rollBack();
            throw $e; // エラーを再投げて呼び出し元に通知
        }
    }

    public function deleteUserUnusedTags(User $user): void
    {
        // まず、使用されていない（他のメモに紐付いていない）、現在のユーザーによって作成された
        // かつ、Adminユーザーによって作成されていないタグを検索します。
        $unusedTagIds = Tag::whereDoesntHave('memos') // memosリレーションを持たないタグを選択
        ->where('created_by', $user->id) // 現在のユーザーによって作成された
        ->where('created_by', '!=', TagConst::ADMIN_ID) // Adminによって作成されていない
        ->pluck('id'); // 不要なタグのIDを取得

        // 条件に一致するタグを削除します。
        // pluck('id')により取得したIDリストを使用してdelete()を呼び出すことで、対象となるタグを一括で削除する
        if ($unusedTagIds->isNotEmpty()) {
            Tag::whereIn('id', $unusedTagIds)->delete();
        }
    }
}
