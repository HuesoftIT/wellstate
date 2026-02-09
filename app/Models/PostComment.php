<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'post_id',
        'customer_id',
        'parent_id',
        'content',
        'is_approved',
        'is_spam',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'is_spam'     => 'boolean',
    ];

    /* ================= RELATIONS ================= */

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id')->withTrashed();
    }

    public function replies()
    {
        return $this->hasMany(self::class, 'parent_id')->withTrashed();
    }

    /* ================= SCOPES ================= */

    public function scopeApproved($query)
    {
        return $query
            ->where('is_approved', 1)
            ->where('is_spam', 0);
    }
}
