<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Str;

class Branch extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'phone',
        'address',
        'latitude',
        'longitude',
        'open_time',
        'close_time',
        'is_active',
        'note',
        'image',
    ];


    protected $casts = [
        'is_active' => 'boolean',
        'open_time' => 'datetime:H:i',
        'close_time' => 'datetime:H:i',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    protected static function booted()
    {
        static::creating(function ($branch) {
            if (empty($branch->code)) {
                $branch->code = self::generateCode();
            }
        });
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'branch_id');
    }
    public function workingShifts(){
        return $this->hasMany(WorkingShift::class, 'branch_id');
    }
    
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function roomTypes()
    {
        return $this->belongsToMany(RoomType::class, 'branch_room_types')
            ->withPivot(['price', 'capacity', 'is_active'])
            ->withTimestamps()
            ->wherePivotNull('deleted_at');
    }



    protected static function generateCode(): string
    {
        $lastId = DB::table('branches')->max('id') ?? 0;

        $nextNumber = $lastId + 1;

        return 'BR-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    static public function uploadAndResize($image, $width = 450, $height = null)
    {
        if (!$image) {
            return null;
        }

        $disk = Storage::disk('public');
        $folder = 'images/branches';

        // Tạo folder nếu chưa có
        if (!$disk->exists($folder)) {
            $disk->makeDirectory($folder);
        }

        $timestamp = Carbon::now()->format('Y-m-d-H-i-s');
        $extension = $image->getClientOriginalExtension();
        $filename  = Str::slug(
            pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)
        );

        $path = "{$folder}/{$timestamp}-{$filename}.{$extension}";

        // Resize image
        $img = Image::make($image)
            ->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->encode($extension);

        // Lưu bằng Storage
        $disk->put($path, (string) $img);

        // DB chỉ lưu path tương đối
        return $path;
    }
}
