<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $user_id
 * @property int $career_id
 * @property int $gender_id
 * @property int $dominant_hand_id
 * @property int $play_frequency_id
 * @property int $tennis_level_id
 */
class ProfileResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'career_id' => $this->career_id,
            'gender_id' => $this->gender_id,
            'dominant_hand_id' => $this->dominant_hand_id,
            'play_frequency_id' => $this->play_frequency_id,
            'tennis_level_id' => $this->tennis_level_id,
        ];
    }
}
