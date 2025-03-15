<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'name',
        'username',
        'profile_photo',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
