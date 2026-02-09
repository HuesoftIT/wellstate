<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeWorkingShift extends Model
{
    protected $fillable = [
        'employee_id',
        'working_shift_id',
        'work_date',
    ];

    protected $casts = [
        'work_date' => 'date',
    ];


    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function workingShift()
    {
        return $this->belongsTo(WorkingShift::class);
    }
}
