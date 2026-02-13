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
    // static public function uploadAndResize($image)
    // {
    //     if (!$image) {
    //         return null;
    //     }

    //     $disk = Storage::disk('public');
    //     $folder = 'images/wellstate_slides';

    //     if (!$disk->exists($folder)) {
    //         $disk->makeDirectory($folder);
    //     }

    //     $timestamp = Carbon::now()->format('Y-m-d-H-i-s');
    //     $extension = $image->getClientOriginalExtension();
    //     $filename  = Str::slug(
    //         pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)
    //     );

    //     $path = "{$folder}/{$timestamp}-{$filename}.{$extension}";

    //     $img = InterventionImage::make($image)
    //         ->encode($extension, 90); // quality 90

    //     $disk->put($path, (string) $img);

    //     return $path;
    // }

    static public function uploadAndResize($image)
    {
        if (!$image) {
            return null;
        }

        $disk = Storage::disk('public');
        $folder = 'images/wellstate_slides';

        if (!$disk->exists($folder)) {
            $disk->makeDirectory($folder);
        }

        $timestamp = Carbon::now()->format('Y-m-d-H-i-s');
        $extension = 'webp'; // convert luôn sang webp
        $filename  = Str::slug(
            pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)
        );

        $path = "{$folder}/{$timestamp}-{$filename}.{$extension}";

        $img = InterventionImage::make($image);

        if ($img->width() > 1920) {
            $img->resize(1920, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        $img->encode('webp', 90);

        $disk->put($path, (string) $img);

        return $path;
    }
}
