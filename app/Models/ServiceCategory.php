<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;
use Str;

class ServiceCategory extends Model
{
    use HasFactory, SoftDeletes, Sortable;

    protected $table = 'service_categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
    public $sortable = [
        'name',
        'order',
        'created_at',
        'updated_at',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    // Quan hệ với bảng services
    public function services()
    {
        return $this->hasMany(Service::class);
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = $model->generateUniqueSlug($model->name);
            }
        });
    }


    private function generateUniqueSlug(string $name): string
    {
        $slug = \Str::slug($name);
        $original = $slug;
        $i = 1;

        while (
            static::where('slug', $slug)->exists()
        ) {
            $slug = $original . '-' . $i;
            $i++;
        }

        return $slug;
    }
}
