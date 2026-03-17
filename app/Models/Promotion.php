<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Str;

class Promotion extends Model
{
    use HasFactory, SoftDeletes;
    const APPLY_SCOPE_BOOKING = 'booking';
    const APPLY_SCOPE_ROOM = 'room';
    const APPLY_SCOPE_SERVICE = 'service';

    public const APPLY_SCOPES = [
        self::APPLY_SCOPE_BOOKING,
        self::APPLY_SCOPE_ROOM,
        self::APPLY_SCOPE_SERVICE,
    ];

    public const APPLY_SCOPE_LABELS = [
        self::APPLY_SCOPE_BOOKING => 'Toàn bộ booking',
        self::APPLY_SCOPE_ROOM => 'Phòng',
        self::APPLY_SCOPE_SERVICE => 'Dịch vụ',
    ];
    protected $fillable = [
        'title',
        'image',
        'content',
        'apply_scope',
        'discount_code',
        'discount_max_uses',
        'discount_uses_count',
        'discount_max_uses_per_user',
        'discount_min_order_value',
        'discount_type', // percent / fixed
        'discount_value',
        'discount_max_value',
        'start_date',
        'end_date',
        'is_active',
        'is_visible',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function getApplyScopeLabelAttribute()
    {
        switch ($this->apply_scope) {
            case self::APPLY_SCOPE_ROOM:
                return '<span class="badge badge-info" style="font-size:14px;padding:6px 10px;">
                        <i class="fa fa-bed" style="font-size:14px;"></i> Room
                    </span>';

            case self::APPLY_SCOPE_SERVICE:
                return '<span class="badge badge-warning" style="font-size:14px;padding:6px 10px;">
                        <i class="fa fa-concierge-bell" style="font-size:14px;"></i> Service
                    </span>';

            case self::APPLY_SCOPE_BOOKING:
                return '<span class="badge badge-success" style="font-size:14px;padding:6px 10px;">
                        <i class="fa fa-calendar-check" style="font-size:14px;"></i> Booking
                    </span>';

            default:
                return '<span class="badge badge-secondary" style="font-size:14px;padding:6px 10px;">'
                    . ucfirst($this->apply_scope) .
                    '</span>';
        }
    }

    public function getDiscountDisplayAttribute()
    {
        if ($this->discount_type === 'percent') {
            return '<span class="badge badge-success">'
                . number_format($this->discount_value) . '%'
                . '</span>';
        }

        return '<span class="text-primary font-weight-bold">'
            . number_format($this->discount_value) . ' đ'
            . '</span>';
    }
    public function rules()
    {
        return $this->hasMany(PromotionRule::class)
            ->orderBy('order');
    }

    static public function uploadAndResize($image, $width = 450, $height = null)
    {
        if (!$image) {
            return null;
        }

        $disk = Storage::disk('public');
        $folder = 'images/promotions';

        if (!$disk->exists($folder)) {
            $disk->makeDirectory($folder);
        }

        $timestamp = Carbon::now()->format('Y-m-d-H-i-s');
        $extension = $image->getClientOriginalExtension();
        $filename  = Str::slug(
            pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)
        );

        $path = "{$folder}/{$timestamp}-{$filename}.{$extension}";

        $img = Image::make($image)
            ->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->encode($extension);

        $disk->put($path, (string) $img);

        return $path;
    }

    public function getStatusAttribute(): string
    {
        if (! $this->is_active) {
            return 'disabled';
        }

        $now = Carbon::now();

        if ($this->start_date && $now->lt($this->start_date)) {
            return 'upcoming';
        }

        if ($this->end_date && $now->gt($this->end_date)) {
            return 'expired';
        }

        return 'active';
    }

    public function getStatusTextAttribute(): string
    {
        switch ($this->status) {
            case 'disabled':
                return 'Tắt';
            case 'upcoming':
                return 'Sắp diễn ra';
            case 'expired':
                return 'Hết hạn';
            default:
                return 'Đang áp dụng';
        }
    }


    public function scopeStatus($query, $status)
    {
        $now = now();

        if ($status === 'active') {
            return $query->where('is_active', 1)
                ->where('start_date', '<=', $now)
                ->where(function ($q) use ($now) {
                    $q->whereNull('end_date')
                        ->orWhere('end_date', '>=', $now);
                });
        }

        if ($status === 'upcoming') {
            return $query->where('is_active', 1)
                ->where('start_date', '>', $now);
        }

        if ($status === 'expired') {
            return $query->where('end_date', '<', $now);
        }

        if ($status === 'disabled') {
            return $query->where('is_active', 0);
        }

        return $query;
    }


    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function usages()
    {
        return $this->hasMany(PromotionUsage::class);
    }
}
