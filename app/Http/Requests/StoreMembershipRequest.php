<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMembershipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('memberships')
                    ->whereNull('deleted_at')
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'min_total_spent' => [
                'required',
                'numeric',
                'min:0',
            ],
            'priority' => [
                'required',
                'integer',
                'min:0',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'is_active' => [
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Vui lòng nhập mã membership.',
            'code.unique' => 'Mã membership đã tồn tại.',
            'code.max' => 'Mã membership không được vượt quá :max ký tự.',

            'name.required' => 'Vui lòng nhập tên membership.',
            'name.max' => 'Tên membership không được vượt quá :max ký tự.',

            'min_total_spent.required' => 'Vui lòng nhập mức chi tiêu tối thiểu.',
            'min_total_spent.numeric' => 'Chi tiêu tối thiểu phải là số.',
            'min_total_spent.min' => 'Chi tiêu tối thiểu không được nhỏ hơn 0.',

            'priority.required' => 'Vui lòng nhập độ ưu tiên.',
            'priority.integer' => 'Độ ưu tiên phải là số nguyên.',
            'priority.min' => 'Độ ưu tiên không được nhỏ hơn 0.',

            'is_active.boolean' => 'Trạng thái kích hoạt không hợp lệ.',
        ];
    }
}
