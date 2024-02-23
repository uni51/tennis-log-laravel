<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $tag = $this->faker->unique()->word;

        return [
            'name' => $tag,
            'normalized' => mb_strtolower($tag),
            'created_by' => User::inRandomOrder()->first()->id,
        ];
    }
}
