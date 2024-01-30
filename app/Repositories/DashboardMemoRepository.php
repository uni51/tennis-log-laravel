<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Memo;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
     * @param array $validated
     * @throws Exception
     * @return void
     */
    public function createMemo(array $validated): void
    {
        try {
            DB::beginTransaction();

            // モデルクラスのインスタンス化
            $memo = new Memo();
            // パラメータのセット
            $memo->user_id = Auth::id();
            $memo->category_id = $validated['category_id'];
            $memo->status = $validated['status_id'];
            $memo->title = $validated['title'];
            $memo->body = $validated['body'];
            $memo->save();

            // メモとタグの紐付け
            if (!empty($validated['tags'])) {
                $memo->tag($validated['tags']);
            }
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            throw new Exception('メモの登録に失敗しました。');
        }
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
