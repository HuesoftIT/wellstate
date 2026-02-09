<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as InterventionImage;
use Str;

class Image extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'image_category_id',
        'title',
        'image',
        'link',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',

    ];
    public function category()
    {
        return $this->belongsTo(ImageCategory::class, 'image_category_id');
    }
    static public function uploadAndResize($image, $width = 450, $height = null)
    {
        if (!$image) {
            return null;
        }

        $disk = Storage::disk('public');
        $folder = 'images/wellstate_images';

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
        $img = InterventionImage::make($image)
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
