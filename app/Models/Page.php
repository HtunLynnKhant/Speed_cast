<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Page extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'status',
        'page_no',
        'category_id',
        'description'
    ];

    // Page.php (Model)
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
