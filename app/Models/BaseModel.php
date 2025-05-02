<?php

namespace App\Models;

use App\Enums\RecordStatus;
use App\Casts\RecordStatusCast;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected $hidden = [
        'deleted_at'
    ];

    protected $casts = [
        'status' => RecordStatusCast::class
    ];

    public function scopeActive($query)
    {
        return $query->where('status', RecordStatus::ACTIVE);
    }
}
