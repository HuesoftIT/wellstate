<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Str;

class Employee extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'branch_id',
        'code',
        'name',
        'avatar',
        'phone',
        'is_active',
    ];


    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function services()
    {
        return $this->belongsToMany(
            Service::class,
            'employee_services'
        );
    }

    public function workingShifts()
    {
        return $this->belongsToMany(
            WorkingShift::class,
            'employee_working_shifts'
        )->withPivot('work_date')
            ->withTimestamps();
    }



    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    static public function uploadAndResize($image, $width = 450, $height = null)
    {
        if (!$image) {
            return null;
        }

        $disk = Storage::disk('public');
        $folder = 'images/employees';

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
