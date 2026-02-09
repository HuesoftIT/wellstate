<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceComboItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'service_combo_items';

    protected $fillable = [
        'combo_service_id',
        'service_id',
        'quantity'
    ];

    // Quan hệ với bảng services
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // Quan hệ với bảng service_combos
    public function serviceCombo()
    {
        return $this->belongsTo(ServiceCombo::class, 'combo_service_id');
    }
}
