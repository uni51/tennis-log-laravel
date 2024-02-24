<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Memo;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class MemoTagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $memoIds = Memo::pluck('id')->all();
        $tagIds = Tag::pluck('id')->all();

        // 仮に100回の挿入を試みる
        for ($i = 0; $i < 100; $i++) {
            $memoId = $memoIds[array_rand($memoIds)];
            $tagId = $tagIds[array_rand($tagIds)];

            try {
                DB::table('memo_tag')->insert([
                    'memo_id' => $memoId,
                    'tag_id' => $tagId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (QueryException $e) {
                // 一意性違反エラーの場合はスキップ
                if ($e->errorInfo[1] == 1062) {
                    continue;
                }

                // 他のエラーの場合は例外を再投げ
                throw $e;
            }
        }
    }
}
