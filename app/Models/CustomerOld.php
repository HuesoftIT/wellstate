<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Kyslik\ColumnSortable\Sortable;
use Carbon\Carbon;

class CustomerOld extends Authenticatable
{
    use HasFactory, Sortable;

    protected $table = 'customers';
    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'avatar',
        'address',
        'permanent_address',
        'password',
        'code',
        'gender',
        'language_id',
        'agent_id',
        'enable_notification',
        'active',
        'money',
        'referral_id',
        'referral_code',
    ];

    protected $hidden = [
        'password',
        'token',
        'remember_token',
    ];

    public $sortable = [
        'id',
        'name',
        'email',
        'phone',
        'active',
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'total_spent' => 'float',
    ];
    

    public function getTextGenderAttribute(): string
    {
        return match ($this->gender) {
            1 => __('message.user.gender_male'),
            0 => __('message.user.gender_female'),
            default => '',
        };
    }

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

   
    public function showAvatar(): ?string
    {
        if ($this->avatar && \Storage::exists($this->avatar)) {
            return '<img width="40" src="' . asset(\Storage::url($this->avatar)) . '">';
        }

        return null;
    }
}
