<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed $id
 * @method static where(string $string, string $nickName)
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firebase_uid',
        'name',
        'nickname',
        'email',
        'email_verified_at',
        'password',
        'count_inappropriate_posts',
        'total_times_notified_to_fix',
        'total_times_attempt_to_fix',
        'total_times_delete_memos_by_admin',
        'total_times_delete_tag_by_admin',
        'times_warned',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function providers(): HasMany
    {
        return $this->hasMany(OAuthProvider::class);
    }

    public function memos()
    {
        return $this->hasMany(Memo::class);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
}
