<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Str;

class UpdateServiceCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (empty($this->slug) && !empty($this->name)) {
            $this->merge([
                'slug' => Str::slug($this->name),
            ]);
        }
    }

    public function rules(): array
    {
        $category = $this->route('service_category');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('service_categories', 'name')
                    ->ignore($category)
                    ->whereNull('deleted_at'),
            ],
            'slug' => [
                'nullable',
                Rule::unique('service_categories', 'slug')
                    ->ignore($category)
                    ->whereNull('deleted_at'),
            ],
            'description' => 'nullable|string',
            'order'       => 'integer|min:0',
            'is_active'   => 'boolean',
        ];
    }



    public function messages(): array
    {
        return [
            'name.required' => 'Tên loại dịch vụ là bắt buộc',
            'name.unique'   => 'Tên loại dịch vụ đã tồn tại',
            'name.max'      => 'Tên loại dịch vụ tối đa :max ký tự',

            'slug.unique'   => 'Slug đã tồn tại',

            'order.integer' => 'Thứ tự phải là số nguyên',
            'order.min'     => 'Thứ tự phải lớn hơn hoặc bằng 0',

            'is_active.boolean' => 'Trạng thái không hợp lệ',
        ];
    }
}
