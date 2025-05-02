<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'ads_id',
        'date',
        'amount',
        'payment_type',
        'is_fully_paid',
        'currency_id',
        'status'
    ];

    public function ads()
    {
        return $this->belongsTo(Ads::class, 'ads_id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

}
