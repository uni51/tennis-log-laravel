<?php
namespace Database\Factories;

use App\Models\Memo;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

class MemoTagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $memoIds = Memo::pluck('id')->all();
        $tagIds = Tag::pluck('id')->all();

        return [
            'memo_id' => $this->faker->randomElement($memoIds),
            'tag_id' => $this->faker->randomElement($tagIds),
        ];
    }
}
