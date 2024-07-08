<?php

namespace Database\Seeders;

use App\Consts\SeederConst;
use Illuminate\Database\Seeder;
use App\Models\Memo;

class MemosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Memo::factory(SeederConst::MAKE_MEMO_COUNT)->create();
    }
}
