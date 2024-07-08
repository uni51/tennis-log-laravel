<?php
namespace App\Repositories;

use App\Enums\MemoAdminReviewStatusType;
use App\Enums\MemoChatGptReviewStatusType;
use App\Models\Memo;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProfileRepository
{
    /**
     * @param array $validated
     * @return Profile
     */
    public function createProfile(array $validated): Profile
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

    /**
     * @return Profile
     */
    public function getProfile(): Profile
    {
        $result = Profile::where('user_id', Auth::id())->first();
        return $result ?? new Profile;
    }

    public function editProfile(array $validated, User $user): void
    {
        $profile = Profile::where('user_id', $user->id)->first();

        $profile->fill($validated);
        $profile->save();
    }
}
