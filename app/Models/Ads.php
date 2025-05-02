<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ads extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Define the fillable fields for mass assignment
    protected $fillable = [
        'title',
        'project_id',
        'total_price',
        'total_final_price',
        'discount',
        'content_type_id',
        'content_path',
        'ads_type_id',
        'approval_status',
        'subscription_id',
        'payment_status',
        'remark',
        'status',
        'page_id',
        'category_id',
        'Is_Approve'
    ];

    /**
     * Relationship with Subscription (One-to-One)
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1); // Assuming 'status' is 1 for active ads
    }
    public function subscription()
    {
        return $this->hasOne(Subscription::class, 'id', 'subscription_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    /**
     * Relationship with Payment (One-to-Many)
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'ads_id', 'id');
    }

    /**
     * Relationship with Currency (Many-to-One)
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    /**
     * Relationship with AdsType (Many-to-One)
     */
    public function adsType()
    {
        return $this->belongsTo(AdsType::class, 'ads_type_id');
    }

    /**
     * Relationship with Project (Many-to-One)
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function sub()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }

    public function isImage()
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        return in_array(pathinfo($this->content_path, PATHINFO_EXTENSION), $imageExtensions);
    }

    public function isVideo()
    {
        $videoExtensions = ['mp4', 'webm', 'ogg'];

        return in_array(pathinfo($this->content_path, PATHINFO_EXTENSION), $videoExtensions);
    }
}
