<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class AdsType extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'status',
        'description'
    ];
    protected $hidden = [
        'deleted_at'
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 1); // Assuming 1 means active
    }
}
