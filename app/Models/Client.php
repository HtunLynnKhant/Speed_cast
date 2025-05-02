<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    // The table associated with the model.
    protected $table = 'clients';

    // The attributes that are mass assignable.
    protected $fillable = [
        'company_name',
        'registration_number',
        'allow_type_of_ads_set',
        'user_id',
    ];

    protected $casts = [
        'allow_type_of_ads_set' => 'array', // This will automatically cast the field to an array
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
