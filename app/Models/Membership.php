<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Membership extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'memberships';

    protected $fillable = [
        'code',
        'name',
        'min_total_spent',
        'priority',
        'benefits',
        'description',
        'is_active',
    ];

    protected $casts = [
        'benefits' => 'array',
        'is_active' => 'boolean',
        'min_total_spent' => 'decimal:2',
    ];

    /**
     * Scope: chỉ lấy membership đang active
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: sắp xếp theo level cao → thấp
     */
    public function scopeOrderByPriorityDesc($query)
    {
        return $query->orderByDesc('priority');
    }
}
