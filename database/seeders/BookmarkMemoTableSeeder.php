<?php

namespace Database\Seeders;

use App\Consts\SeederConst;
use Illuminate\Database\Seeder;
use App\Models\Memo;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class BookmarkMemoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $memoIds = Memo::pluck('id')->all();
        $userIds = User::pluck('id')->all();

        // 仮に1000回の挿入を試みる
        for ($i = 0; $i < SeederConst::MAKE_MEMO_COUNT; $i++) {
            $memoId = $memoIds[array_rand($memoIds)];
            $userId = $userIds[array_rand($userIds)];

            try {
                DB::table('bookmark_memo')->insert([
                    'user_id' => $userId,
                    'memo_id' => $memoId,
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
