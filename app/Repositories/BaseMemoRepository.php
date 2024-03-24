<?php
namespace App\Repositories;

use App\Consts\TagConst;
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
            abort(404, '指定されたIDのメモが見つかりません。');
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

    protected function deleteMemoTags(Memo $memo): void
    {
        // memo_tag テーブルから該当メモに関連するタグのレコードをすべて取得
        $memoTags = DB::table('memo_tag')->where('memo_id', $memo->id)->get();

        // バックアップ用のデータを準備
        $backupData = $memoTags->map(function ($memoTag) {
            return [
                'force_deleted' => true,
                'memo_id' => $memoTag->memo_id,
                'tag_id' => $memoTag->tag_id,
                'memo_tag_created_at' => $memoTag->created_at,
                'memo_tag_updated_at' => $memoTag->updated_at,
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ];
        })->toArray();

        // deleted_memo_tag にバックアップデータをバルクインサート
        DB::table('deleted_memo_tag')->insert($backupData);

        // memo_tag テーブルから該当メモに関連するすべてのレコードをバッチ削除
        DB::table('memo_tag')->where('memo_id', $memo->id)->delete();
    }

    protected function deleteUnusedTagsBelongsToUser(User $user): void
    {
        // 使用されていないタグを取得
        $unusedTags = Tag::whereDoesntHave('memos')
            ->where('created_by', $user->id)
            ->where('created_by', '!=', TagConst::ADMIN_ID)
            ->get(['id', 'name', 'created_by', 'created_at', 'updated_at'])->toArray();

        // deleted_tags へのバルクインサートデータを準備
        $deletedTags = array_map(function ($unusedTag) {
            return [
                'force_deleted' => true,
                'tag_id' => $unusedTag['id'],
                'name' => $unusedTag['name'],
                'created_by' => $unusedTag['created_by'],
                'tag_created_at' => Carbon::parse($unusedTag['created_at'])->format('Y-m-d H:i:s'),
                'tag_updated_at' => Carbon::parse($unusedTag['updated_at'])->format('Y-m-d H:i:s'),
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ];
        }, $unusedTags);

        // バルクインサートでdeleted_tagsに追加
        DB::table('deleted_tags')->insert($deletedTags);

        // 元のタグを削除
        Tag::whereIn('id', array_column($unusedTags, 'id'))->delete();
    }
}
