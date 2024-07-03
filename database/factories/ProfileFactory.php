<?php

namespace Database\Factories;

use App\Enums\Profile\GenderType;
use App\Enums\Profile\CareerType;
use App\Enums\Profile\DominantHandType;
use App\Enums\Profile\PlayFrequencyType;
use App\Enums\Profile\TennisLevelType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $validCareerIds = array_filter(CareerType::getValues(), function ($value) {
            return $value !== CareerType::UNSELECTED;
        });

        $validGenderIds = array_filter(GenderType::getValues(), function ($value) {
            return $value !== GenderType::UNSELECTED;
        });

        $validDominateHandIds = array_filter(DominantHandType::getValues(), function ($value) {
            return $value !== DominantHandType::UNSELECTED;
        });

        $validPlayFrequencyIds = array_filter(PlayFrequencyType::getValues(), function ($value) {
            return $value !== PlayFrequencyType::UNSELECTED;
        });

        $tennisLevelIds = array_filter(TennisLevelType::getValues(), function ($value) {
            return $value !== TennisLevelType::UNSELECTED;
        });

        return [
            'user_id'           => $this->faker->unique()->numberBetween(1, 21),
            'career_id'         => Arr::random($validCareerIds),
            'gender_id'         => Arr::random($validGenderIds),
            'dominant_hand_id'  => Arr::random($validDominateHandIds),
            'play_frequency_id' => Arr::random($validPlayFrequencyIds),
            'tennis_level_id'   => Arr::random($tennisLevelIds),
        ];
    }
}
