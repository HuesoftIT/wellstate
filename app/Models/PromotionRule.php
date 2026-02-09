<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PromotionRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'promotion_id',
        'type',
        'config',
        'order',
    ];

    protected $casts = [
        'config' => 'array',
    ];

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }
}
