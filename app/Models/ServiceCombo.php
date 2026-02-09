<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceCombo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'service_combos';

    protected $fillable = [
        'name',
        'price',
        'sale_price',
        'description',
        'is_active'
    ];

    // Quan hệ với bảng services thông qua bảng trung gian service_combo_items
    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_combo_items')
            ->withPivot('quantity');
    }
}
