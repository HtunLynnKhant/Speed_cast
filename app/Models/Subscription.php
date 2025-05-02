<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Define the table associated with the model
    protected $table = 'subscriptions';

    // Define the fillable fields
    protected $fillable = [
        'active_from',
        'end_on',
        'status',
    ];

    // Define the dates for soft deletes
    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at',
        'active_from',
        'end_on',
    ];
    protected $casts = [
        'active_from' => 'datetime:Y-m-d',
        'end_on' => 'datetime:Y-m-d',
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
