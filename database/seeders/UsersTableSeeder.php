<?php

namespace Database\Seeders;

use App\Consts\SeederConst;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(SeederConst::MAKE_USER_COUNT)->create();

        // 追加でテストユーザーを作成
        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'nickname' => 'testuser',
            'email' => 'test@example.com',
        ]);
    }
}
