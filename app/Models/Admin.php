<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
/*
  Illuminate\Auth\Notifications\AdminResetPassword は、Illuminate\Auth\Notifications\ResetPassword
  を自分でコピーして作成したファイル
  See: https://omokaji.atlassian.net/wiki/spaces/PRODUCT/pages/1965817898/Laravel+Breeze+Multi+Authentification
*/
use Illuminate\Auth\Notifications\AdminResetPassword as ResetPasswordNotification;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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

    public function sendPasswordResetNotification($token){

        $this->notify(new ResetPasswordNotification($token));
    }
}
