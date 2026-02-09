<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeWorkingShiftsTable extends Migration
{
    public function up(): void
    {
        Schema::create('employee_working_shifts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('working_shift_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->date('work_date');

            $table->timestamps();

            $table->unique(
                ['employee_id', 'working_shift_id', 'work_date'],
                'uniq_emp_shift_date'
            );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_working_shifts');
    }
}
