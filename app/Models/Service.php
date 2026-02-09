<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Kyslik\ColumnSortable\Sortable;
use Str;
use Intervention\Image\Facades\Image;

class Service extends Model
{
    use HasFactory, SoftDeletes, Sortable;

    protected $table = 'services';

    protected $fillable = [
        'service_category_id',
        'title',
        'slug',
        'description',
        'duration',
        'price',
        'sale_price',
        'image',
        'is_combo',
        'is_active'
    ];

    public $sortable = [
        'title',
        'created_at',
        'updated_at',
    ];

    public function scopeActive($query) {
        return $query->where('is_active', true);
    }
    public function serviceCategory()
    {
        return $this->belongsTo(ServiceCategory::class);
    }

    public function comboItems()
    {
        return $this->hasMany(ServiceComboItem::class, 'combo_service_id', 'id');
    }

    public function employees()
    {
        return $this->belongsToMany(
            Employee::class,
            'employee_services'
        );
    }


    static public function uploadAndResize($image, $width = 450, $height = null)
    {
        if (!$image) {
            return null;
        }

        $disk = Storage::disk('public');
        $folder = 'images/services';

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
