<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CatgoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'id'   => 1,
            'name' => 'フォアハンド',
        ]);
        Category::create([
            'id'   => 2,
            'name' => '両手バックハンド',
        ]);
        Category::create([
            'id'   => 3,
            'name' => '片手バックハンド',
        ]);
        Category::create([
            'id'   => 4,
            'name' => 'サーブ',
        ]);
        Category::create([
            'id'   => 5,
            'name' => 'リターン',
        ]);
        Category::create([
            'id'   => 6,
            'name' => 'ボレー',
        ]);
        Category::create([
            'id'   => 7,
            'name' => 'スマッシュ',
        ]);
        Category::create([
            'id'   => 8,
            'name' => 'シングルス',
        ]);
        Category::create([
            'id'   => 9,
            'name' => 'ダブルス',
        ]);
        Category::create([
            'id'   => 10,
            'name' => 'ギア',
        ]);
        Category::create([
            'id'   => 99,
            'name' => 'その他',
        ]);
    }
}
