<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;

class RoomType extends Model
{
    use HasFactory, Sortable, SoftDeletes;

    protected $table = 'room_types';

    protected $fillable = [
        'code',
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public $sortable = [
        'code',
        'name',
        'is_active',
        'created_at',
        'updated_at',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'branch_room_types')
            ->withPivot(['price', 'capacity', 'is_active'])
            ->withTimestamps()
            ->wherePivotNull('deleted_at');
    }

    public function branchRoomTypes()
    {
        return $this->hasMany(BranchRoomType::class, 'room_type_id');
    }

    public function activeBranchRoomTypes()
    {
        return $this->branchRoomTypes()->where('is_active', 1);
    }
}
