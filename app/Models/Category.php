<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'status',
        'active_icon_path',
        'default_icon_path',
        'description'
    ];

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class, 'category_id', 'id');
    }

    public function ads(): HasMany
    {
        return $this->hasMany(Ads::class);
    }
}
