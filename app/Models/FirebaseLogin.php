<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
