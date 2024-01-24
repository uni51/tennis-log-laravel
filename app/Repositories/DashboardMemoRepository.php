<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Memo;

class DashboardMemoRepository
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
     * @param Memo $memo
     * @param array $validated
     * @return bool
     */
    public function updateMemo(Memo $memo, array $validated): bool
    {
        $memo->title = $validated['title'];
        $memo->body = $validated['body'];
        $memo->category_id = $validated['category_id'];
        $memo->status = $validated['status_id'];
        return $memo->update();
    }
}
