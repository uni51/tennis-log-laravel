<?php

namespace Database\Seeders;

use App\Consts\SeederConst;
use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tag::factory(SeederConst::MAKE_TAG_COUNT)->create();
    }
}
