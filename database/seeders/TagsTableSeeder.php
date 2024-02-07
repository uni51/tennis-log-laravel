<?php

namespace Database\Seeders;

use Cviebrock\EloquentTaggable\Models\Tag;
use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 100; $i++) {
            Tag::create([
                'name' => 'Unique_tag_' . $i,
                'normalized' => mb_strtolower('Unique_tag_' . $i),
            ]);
        }
    }
}
