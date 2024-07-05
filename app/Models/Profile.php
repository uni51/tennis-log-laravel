<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static create(array $array)
 */
class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'career_id',
        'gender_id',
        'dominant_hand_id',
        'play_frequency_id',
        'tennis_level_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
