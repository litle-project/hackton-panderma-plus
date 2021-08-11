<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{
    protected $table = 'donors';
    protected $primaryKey = 'donor_id';
    protected $fillable = [
        'user_id',
        'category_id',
        'type',
        'cover',
        'title',
        'phone',
        'description',
        'total_need',
        'address',
        'latitude',
        'longitude',
        'deadline',
        'created_at',
        'updated_at',
    ];
}
