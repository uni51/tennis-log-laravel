<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Enums\CategoryType;

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
            'id'   => CategoryType::FOREHAND,
            'name' => CategoryType::getDescription(CategoryType::FOREHAND),
        ]);
        Category::create([
            'id'   => CategoryType::DOUBLE_BACKHAND,
            'name' => CategoryType::getDescription(CategoryType::DOUBLE_BACKHAND),
        ]);
        Category::create([
            'id'   => CategoryType::SINGLE_BACKHAND,
            'name' => CategoryType::getDescription(CategoryType::SINGLE_BACKHAND),
        ]);
        Category::create([
            'id'   => CategoryType::SERVE,
            'name' => CategoryType::getDescription(CategoryType::SERVE),
        ]);
        Category::create([
            'id'   => CategoryType::RETURN,
            'name' => CategoryType::getDescription(CategoryType::RETURN),
        ]);
        Category::create([
            'id'   => CategoryType::VOLLEY,
            'name' => CategoryType::getDescription(CategoryType::VOLLEY),
        ]);
        Category::create([
            'id'   => CategoryType::SMASH,
            'name' => CategoryType::getDescription(CategoryType::SMASH),
        ]);
        Category::create([
            'id'   => CategoryType::SINGLES,
            'name' => CategoryType::getDescription(CategoryType::SINGLES),
        ]);
        Category::create([
            'id'   => CategoryType::DOUBLES,
            'name' => CategoryType::getDescription(CategoryType::DOUBLES),
        ]);
        Category::create([
            'id'   => CategoryType::GOODS,
            'name' => CategoryType::getDescription(CategoryType::GOODS),
        ]);
        Category::create([
            'id'   => CategoryType::OTHER,
            'name' => CategoryType::getDescription(CategoryType::OTHER),
        ]);
    }
}
