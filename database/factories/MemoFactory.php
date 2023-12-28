<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Memo>
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
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'category_id' => fake()->numberBetween(1, 9),
            'title' => fake()->realTextBetween(5,50),
            'status' => fake()->numberBetween(0, 3),
            'body' => fake()->realText(),
        ];
    }
}
