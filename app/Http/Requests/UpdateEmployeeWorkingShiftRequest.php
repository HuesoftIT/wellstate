<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeWorkingShiftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id'      => 'required|exists:employees,id',
            'working_shift_id' => 'required|exists:working_shifts,id',
            'work_date'        => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute không được để trống',
            'exists'   => ':attribute không hợp lệ',
            'date'     => ':attribute không đúng định dạng ngày',
        ];
    }

    public function attributes(): array
    {
        return [
            'employee_id'      => 'Nhân viên',
            'working_shift_id' => 'Ca làm việc',
            'work_date'        => 'Ngày làm việc',
        ];
    }
}
