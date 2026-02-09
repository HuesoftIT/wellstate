<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'fullname',
        'email',
        'phone',
        'message',
        'is_read',
        'is_spam',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_spam' => 'boolean',
    ];

    /* ================= SCOPES ================= */

    public function scopeUnread($query)
    {
        return $query->where('is_read', 0);
    }

    public function scopeNotSpam($query)
    {
        return $query->where('is_spam', 0);
    }
}
