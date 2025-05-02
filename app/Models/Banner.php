<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Banner extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'status',
        'description',
        'video_link'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function contents(): HasMany
    {
        return $this->hasMany(BannerContent::class, 'banner_id', 'id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function subbanners()
    {
        return $this->hasMany(Subbanner::class);
    }
}
