<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'image',
        'is_active',
        'published_at',
        'post_category_id',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'published_at' => 'datetime',
    ];


    public function category()
    {
        return $this->belongsTo(PostCategory::class, 'post_category_id');
    }


    public function scopePublished($query)
    {
        return $query
            ->where('is_active', 1)
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }
    public function comments()
    {
        return $this->hasMany(PostComment::class);
    }

    public function approvedComments()
    {
        return $this->hasMany(PostComment::class)
            ->approved()
            ->whereNull('parent_id');
    }


    public static function generateSlug(string $title): string
    {
        return Str::slug($title);
    }

    static public function uploadAndResize($image, $width = 450, $height = null)
    {
        if (!$image) {
            return null;
        }

        $disk = Storage::disk('public');
        $folder = 'images/posts';

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
