<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id'    => 'required|exists:employees,id',
            'service_ids'    => 'required|array|min:1',
            'service_ids.*'  => 'exists:services,id',
            'is_active'      => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute không được để trống.',
            'exists'   => ':attribute không tồn tại.',
            'array'    => ':attribute phải là danh sách.',
            'min'      => ':attribute phải có ít nhất 1 giá trị.',
            'boolean'  => ':attribute không hợp lệ.',
        ];
    }

    public function attributes(): array
    {
        return [
            'employee_id'   => 'Nhân viên',
            'service_ids'   => 'Dịch vụ',
            'service_ids.*' => 'Dịch vụ',
            'is_active'     => 'Trạng thái',
        ];
    }
}
