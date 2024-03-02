<?php
namespace App\Repositories;

use App\Models\Memo;

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
        return mb_convert_kana(strtolower($tagName), 'as', 'UTF-8');
    }
}
