<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    use SoftDeletes;

    protected $fillable = [
        'membership_id',
        'code',
        'name',
        'phone',
        'email',
        'avatar',
        'address',
        'gender',
        'birthday',
        'total_spent',
        'point',
        'status',
        'note',
    ];

    protected $casts = [
        'status' => 'boolean',
        'birthday' => 'date',
    ];

    /* ================= RELATIONS ================= */

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
