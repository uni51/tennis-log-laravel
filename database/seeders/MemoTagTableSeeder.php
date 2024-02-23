<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MemoTag;

class MemoTagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MemoTag::factory(3000)->create();
    }
}
