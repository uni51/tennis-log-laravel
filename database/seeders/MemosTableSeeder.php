<?php

namespace Database\Seeders;

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
        \App\Models\Memo::factory(10000)->create();
    }
}
