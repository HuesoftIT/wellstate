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

    protected $fillable = [
        'type', // membership / promotion
        'title',
        'image',
        'content',
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
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];


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


    public function scopeStatus($query, string $status)
    {
        $now = now();

        return match ($status) {
            'active' => $query->where('is_active', 1)
                ->where('start_date', '<=', $now)
                ->where(fn($q) => $q->whereNull('end_date')->orWhere('end_date', '>=', $now)),

            'upcoming' => $query->where('is_active', 1)
                ->where('start_date', '>', $now),

            'expired' => $query->where('end_date', '<', $now),

            'disabled' => $query->where('is_active', 0),

            default => $query
        };
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
