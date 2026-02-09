<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeService extends Model
{
    protected $fillable = [
        'employee_id',
        'service_id',
    ];
    public $timestamps = true;
   
}
