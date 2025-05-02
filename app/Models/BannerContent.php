<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BannerContent extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'type',
        'path',
        'banner_id'
    ];
}
