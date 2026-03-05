<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use SoftDeletes;

    const STATUS_PENDING   = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    const PAYMENT_UNPAID = 'unpaid';
    const PAYMENT_PAID   = 'paid';


    protected static $statusMap = [
        self::STATUS_PENDING => [
            'class' => 'label-warning',
            'text'  => 'Chờ xác nhận',
        ],
        self::STATUS_CONFIRMED => [
            'class' => 'label-success',
            'text'  => 'Đã xác nhận',
        ],
        self::STATUS_CANCELLED => [
            'class' => 'label-danger',
            'text'  => 'Đã huỷ',
        ],
        self::STATUS_COMPLETED => [
            'class' => 'label-primary',
            'text'  => 'Hoàn thành',
        ],
    ];

    protected static $paymentMap = [
        self::PAYMENT_UNPAID => [
            'class' => 'label-danger',
            'text'  => 'Chưa thanh toán',
        ],
        self::PAYMENT_PAID => [
            'class' => 'label-success',
            'text'  => 'Đã thanh toán',
        ],
    ];

    public function getStatusInfoAttribute()
    {
        if (isset(self::$statusMap[$this->status])) {
            return self::$statusMap[$this->status];
        }

        return [
            'class' => 'label-default',
            'text'  => 'Không xác định',
        ];
    }

    public function getPaymentInfoAttribute()
    {
        if (isset(self::$paymentMap[$this->payment_status])) {
            return self::$paymentMap[$this->payment_status];
        }

        return [
            'class' => 'label-default',
            'text'  => 'Không xác định',
        ];
    }
    protected $fillable = [
        'booking_code',
        "branch_room_type_id",
        'booker_name',
        'booker_email',
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

    public function canConfirm()
    {
        return $this->status === 'pending';
    }

    public function canConfirmPayment()
    {
        return $this->payment_status === 'unpaid'
            && in_array($this->status, ['pending', 'confirmed']);
    }

    public function canComplete()
    {
        return $this->status === 'confirmed'
            && $this->payment_status === 'paid';
    }

    public function canCancel()
    {
        return !in_array($this->status, ['completed', 'cancelled']);
    }

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
