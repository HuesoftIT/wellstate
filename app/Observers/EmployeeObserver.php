<?php

namespace App\Observers;

use App\Models\Employee;
use Illuminate\Support\Facades\Storage;

class EmployeeObserver
{
    public function creating(Employee $employee)
    {

        if (empty($employee->code)) {
            $nextId = Employee::withTrashed()->max('id') + 1;
            $employee->code = 'NV' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        }


        if (request()->hasFile('avatar')) {
            $employee->avatar = Employee::uploadAndResize(
                request()->file('avatar')
            );
        }
    }

    public function updating(Employee $employee)
    {

        if (request()->hasFile('avatar')) {

            if (!empty($employee->getOriginal('avatar'))) {
                Storage::disk('public')->delete(
                    $employee->getOriginal('avatar')
                );
            }

            $employee->avatar = Employee::uploadAndResize(
                request()->file('avatar')
            );
        }
    }



    public function created(Employee $employee)
    {
        //
    }

    /**
     * Handle the Employee "updated" event.
     *
     * @param  \App\Models\Employee  $employee
     * @return void
     */
    public function updated(Employee $employee)
    {
        //
    }

    /**
     * Handle the Employee "deleted" event.
     *
     * @param  \App\Models\Employee  $employee
     * @return void
     */
    public function deleted(Employee $employee)
    {
        //
    }

    /**
     * Handle the Employee "restored" event.
     *
     * @param  \App\Models\Employee  $employee
     * @return void
     */
    public function restored(Employee $employee)
    {
        //
    }

    /**
     * Handle the Employee "force deleted" event.
     *
     * @param  \App\Models\Employee  $employee
     * @return void
     */
    public function forceDeleted(Employee $employee)
    {
        //
    }
}
