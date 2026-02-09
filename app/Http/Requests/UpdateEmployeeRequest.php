<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $employeeId = $this->route('employee') ?? $this->id;
        return [
            'branch_id' => ['required', 'exists:branches,id'],
            'code' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('employees', 'code')
                    ->ignore($employeeId),
            ],
            'name'      => ['required', 'string', 'max:255'],
            'phone'     => ['nullable', 'string', 'max:20'],
            'avatar'    => ['nullable', 'image', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute là bắt buộc.',
            'unique'   => ':attribute đã tồn tại.',
            'exists'   => ':attribute không hợp lệ.',
            'image'    => ':attribute phải là hình ảnh.',
            'max'      => ':attribute vượt quá dung lượng cho phép.',
            'boolean'  => ':attribute không hợp lệ.',
        ];
    }

    public function attributes(): array
    {
        return [
            'branch_id' => 'Chi nhánh',
            'code'      => 'Mã nhân viên',
            'name'      => 'Tên nhân viên',
            'phone'     => 'Số điện thoại',
            'avatar'    => 'Ảnh đại diện',
            'is_active' => 'Trạng thái',
        ];
    }
}
