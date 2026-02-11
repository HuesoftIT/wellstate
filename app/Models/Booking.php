<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'booking_code',
        "branch_room_type_id",
        'booker_name',
        'booker_phone',
        'customer_id',
        'branch_id',
        'booking_date',
        'start_time',
        'end_time',
        'total_guests',
        'total_duration',
        'subtotal_amount',
        'promotion_code',
        "promotion_id",
        "promotion_snapshot",
        'discount_amount',
        'total_amount',
        'status',
        'payment_status',
        'note',
    ];

    public function guests()
    {
        return $this->hasMany(BookingGuest::class);
    }

    public function services()
    {
        return $this->hasMany(BookingGuestService::class);
    }

    public function payments()
    {
        return $this->hasMany(BookingPayment::class);
    }

    public function guestServices()
    {
        return $this->hasMany(BookingGuestService::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

   

    public function branchRoomType()
    {
        return $this->belongsTo(BranchRoomType::class);
    }
}
