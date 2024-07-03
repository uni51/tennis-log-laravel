<?php

namespace Database\Seeders;

use App\Consts\SeederConst;
use Illuminate\Database\Seeder;

class ProfileTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Profile::factory(SeederConst::MAKE_USER_COUNT + 1)->create();
    }
}
