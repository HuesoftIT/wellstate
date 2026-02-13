<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Str;

class PostCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
        'parent_id',
        'order',
    ];


    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopeChild($query)
    {
        return $query->whereNotNull('parent_id');
    }

    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }


    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')
            ->where('is_active', 1)
            ->orderBy('order');
    }
    public function posts()
    {
        return $this->hasMany(Post::class, 'post_category_id');
    }


    protected static function booted()
    {
        static::saving(function ($category) {
            if (
                empty($category->slug) ||
                $category->isDirty('name')
            ) {
                $category->slug = Str::slug($category->name);
            }
        });
    }
}
