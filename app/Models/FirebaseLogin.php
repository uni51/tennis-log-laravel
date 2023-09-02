<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class FirebaseLogin extends Model
{
    use HasFactory;

    protected $table = 'firebase_logins';

    protected $fillable = [
        'user_id',
        'firebase_uid',
        'token_id',
        'access_token',
        'expires_at',
    ];

    public static function booted()
    {
        parent::boot();
        // firebase_loginテーブルのレコードが削除された場合には、関連するoauth_accessテーブルのレコードも自動で削除されるようにする
        static::deleting(function (FirebaseLogin $firebaseLogin) {
            $firebaseLogin->oauthAccessTokens()->delete();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function oauthAccessTokens(): HasOne
    {
        return $this->hasOne(OAuthAccessTokens::class, 'id', 'token_id');
    }
}
