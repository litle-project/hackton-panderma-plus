<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'banners';
    protected $primaryKey = 'banner_id';
    protected $fillable = [
        'image',
        'created_at',
        'updated_at',
    ];
}
