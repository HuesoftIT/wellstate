<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingGuestServiceRoom extends Model
{
    protected $fillable = [
        'booking_guest_service_id',
        'branch_room_type_id',
        'room_type_name',
        'quantity',
        'price',
    ];

    public function service()
    {
        return $this->belongsTo(BookingGuestService::class);
    }
}
