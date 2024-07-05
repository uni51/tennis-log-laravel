<?php
namespace App\Repositories;

use App\Enums\MemoAdminReviewStatusType;
use App\Enums\MemoChatGptReviewStatusType;
use App\Models\Memo;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;

class ProfileRepository
{
    /**
     * @param array $validated
     * @return Profile
     */
    public function profileCreate(array $validated): Profile
    {
        return Profile::create([
            'user_id' => Auth::id(),
            'career_id' => $validated['career_id'],
            'gender_id' => $validated['gender_id'],
            'dominant_hand_id' => $validated['dominantHand_id'],
            'play_frequency_id' => $validated['playFrequency_id'],
            'tennis_level_id' => $validated['tennisLevel_id'],
        ]);
    }
}
