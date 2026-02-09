<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreServiceCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // hoặc policy sau này
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'order' => $this->order ?? 0,
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('service_categories', 'name')
                    ->whereNull('deleted_at'),
            ],
            'slug' => [
                'nullable',
                Rule::unique('service_categories', 'slug')
                    ->whereNull('deleted_at'),
            ],
            'description' => 'nullable|string',
            'order'       => 'integer|min:0',
            'is_active'   => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên loại dịch vụ là bắt buộc',
            'name.unique' => 'Tên loại dịch vụ đã tồn tại',
            'name.string'   => 'Tên loại dịch vụ không hợp lệ',
            'name.max'      => 'Tên loại dịch vụ tối đa :max ký tự',

            'slug.unique'   => 'Slug đã tồn tại',

            'order.integer' => 'Thứ tự phải là số nguyên',
            'order.min'     => 'Thứ tự phải lớn hơn hoặc bằng 0',

            'is_active.boolean' => 'Trạng thái không hợp lệ',
        ];
    }
}
