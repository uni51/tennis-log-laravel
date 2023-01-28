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
            'name' => 'バックハンド',
        ]);
        Category::create([
            'id'   => 3,
            'name' => 'サーブ',
        ]);
        Category::create([
            'id'   => 4,
            'name' => 'リターン',
        ]);
        Category::create([
            'id'   => 5,
            'name' => 'ボレー',
        ]);
        Category::create([
            'id'   => 6,
            'name' => 'スマッシュ',
        ]);
        Category::create([
            'id'   => 7,
            'name' => 'ゲーム',
        ]);
        Category::create([
            'id'   => 8,
            'name' => 'その他',
        ]);
    }
}
