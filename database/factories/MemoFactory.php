<?php

namespace Database\Factories;

use App\Enums\MemoStatusType;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin>
 */
class MemoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $validStatusIds = MemoStatusType::getValues();

        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'category_id' => Category::inRandomOrder()->first()->id,
            'title' => fake()->realTextBetween(5,50),
            'status' => Arr::random($validStatusIds),
            'body' => fake()->realText(),
        ];
    }
}
