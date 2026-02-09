<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeeWorkingShiftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'branch_id'        => ['required', 'exists:branches,id'],
            'working_shift_id' => ['required', 'exists:working_shifts,id'],

            'employee_ids'     => ['required', 'array', 'min:1'],
            'employee_ids.*'   => ['required', 'exists:employees,id'],

            'apply_type'       => ['required', Rule::in(['single', '7_days', 'month', 'range'])],

            'from_date'        => ['required', 'date'],

            'to_date'          => [
                'required_if:apply_type,range',
                'date',
                'after_or_equal:from_date',
            ],
        ];
    }



    public function messages(): array
    {
        return [
            'required'                   => ':attribute không được để trống',
            'exists'                     => ':attribute không hợp lệ',
            'date'                       => ':attribute không đúng định dạng ngày',

            'employee_ids.array'         => ':attribute phải là danh sách',
            'employee_ids.min'           => 'Phải chọn ít nhất 1 nhân viên',

            'apply_type.in'              => 'Kiểu phân ca không hợp lệ',

            'from_date.required_if'      => 'Vui lòng chọn ngày bắt đầu',
            'to_date.required_if'        => 'Vui lòng chọn ngày kết thúc',
            'from_date.before_or_equal'  => 'Ngày bắt đầu phải nhỏ hơn hoặc bằng ngày kết thúc',
            'to_date.after_or_equal'     => 'Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu',
        ];
    }

    public function attributes(): array
    {
        return [
            'branch_id'        => 'Chi nhánh',
            'working_shift_id' => 'Ca làm việc',
            'apply_type'       => 'Kiểu phân ca',

            'work_date'        => 'Ngày làm việc',
            'from_date'        => 'Từ ngày',
            'to_date'          => 'Đến ngày',

            'employee_ids'     => 'Nhân viên',
            'employee_ids.*'   => 'Nhân viên',
        ];
    }
}
