<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Currency extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'status',
    ];

    // Define the dates for soft deletes
    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];



    public function ads(): HasMany
    {
        return $this->hasMany(Ads::class);
    }
}
