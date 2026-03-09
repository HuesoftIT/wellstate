<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromotionUsage extends Model
{
    protected $table = 'promotion_usages';

    protected $fillable = [
        'promotion_id',
        'booking_id',
        'phone_number',
        'discount_amount',
        'used_at',
    ];

    protected $casts = [
        'discount_amount' => 'decimal:2',
        'used_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
