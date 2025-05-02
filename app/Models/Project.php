<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 1); // Assuming 1 means active
    }
    public function ads(): HasMany
    {
        return $this->hasMany(Ads::class);
    }
}
