<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkingShift extends Model
{
    protected $fillable = [
        'branch_id',
        'name',
        'start_time',
        'end_time',
        'is_active',
    ];

    public $timestamps = true;
    
    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function employeeWorkingShifts()
    {
        return $this->hasMany(EmployeeWorkingShift::class);
    }

    public function employees()
    {
        return $this->belongsToMany(
            Employee::class,
            'employee_working_shifts'
        )->withPivot('work_date')
            ->withTimestamps();
    }
}
