<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;

class BranchRoomType extends Model
{
    use HasFactory,Sortable, SoftDeletes;

    protected $table = 'branch_room_types';

    protected $fillable = [
        'branch_id',
        'room_type_id',
        'price',
        'capacity',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'capacity' => 'integer',
        'is_active' => 'boolean',
    ];

    public $sortable = [
        'id',
        'price',
        'capacity',
        'is_active',
        'updated_at',
    ];

    public function scopeActive($request) {
        return $request->where('is_active', 1);
    }
    // ===== RELATIONS =====

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }
}
