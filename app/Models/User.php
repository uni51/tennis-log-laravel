<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
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
//    use \Askedio\SoftCascade\Traits\SoftCascadeTrait;


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
        'access_token',
        'expires_at',
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

//    protected $softCascade = ['memos'];

    public function providers(): HasMany
    {
        return $this->hasMany(OAuthProvider::class);
    }

    public function memos()
    {
        return $this->hasMany(Memo::class);
    }
}
