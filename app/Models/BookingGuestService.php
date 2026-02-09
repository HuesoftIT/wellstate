<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingGuestService extends Model
{
    protected $fillable = [
        'booking_id',
        'booking_guest_id',
        'service_id',
        'service_name',
        'duration',
        'quantity',
        'price',
        'total_price',
        'start_time',
        'end_time',
        'status',
    ];

    public function guest()
    {
        return $this->belongsTo(BookingGuest::class, 'booking_guest_id');
    }

    public function rooms()
    {
        return $this->hasMany(BookingGuestServiceRoom::class);
    }
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
