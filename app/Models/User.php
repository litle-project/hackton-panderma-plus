<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    protected $fillable = [
        'full_name',
        'email',
        'password',
        'phone',
        'birthday',
        'gender',
        'photo_profile',
        'is_verified',
        'created_at',
        'updated_at',
    ];
}
