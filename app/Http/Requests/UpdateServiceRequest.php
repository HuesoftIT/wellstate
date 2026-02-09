<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'is_active' => $this->has('is_active') ? 1 : 0,
            'is_combo'  => $this->has('is_combo') ? 1 : 0,
        ]);
    }

    public function rules(): array
    {
        return [
            'service_category_id' => 'required|exists:service_categories,id',

            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('services', 'title')
                    ->ignore($this->route('service'))
                    ->where(
                        fn($q) =>
                        $q->where('service_category_id', $this->service_category_id)
                    ),
            ],

            'description' => 'nullable|string',
            'duration'    => 'required|integer|min:1',
            'price'       => 'required|numeric|min:0',
            'sale_price'  => 'nullable|numeric|min:0|lt:price',

            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'is_combo'  => 'boolean',
            'is_active' => 'boolean',

            // 🔥 Combo items
            'combo_items' => [
                Rule::requiredIf(fn() => $this->boolean('is_combo')),
                'array',
            ],

            'combo_items.*.service_id' => [
                Rule::requiredIf(fn() => $this->boolean('is_combo')),
                'exists:services,id',
            ],

            'combo_items.*.quantity' => [
                Rule::requiredIf(fn() => $this->boolean('is_combo')),
                'integer',
                'min:1',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'service_category_id.required' => 'Vui lòng chọn danh mục dịch vụ',
            'service_category_id.exists'   => 'Danh mục dịch vụ không tồn tại',

            'title.required' => 'Tên dịch vụ là bắt buộc',
            'title.max'      => 'Tên dịch vụ tối đa :max ký tự',
            'title.unique'   => 'Tên dịch vụ đã tồn tại trong danh mục này',

            'duration.required' => 'Thời lượng là bắt buộc',
            'duration.integer'  => 'Thời lượng phải là số',
            'duration.min'      => 'Thời lượng phải lớn hơn 0',

            'price.required' => 'Giá dịch vụ là bắt buộc',
            'price.numeric'  => 'Giá dịch vụ không hợp lệ',

            'sale_price.numeric' => 'Giá khuyến mãi không hợp lệ',
            'sale_price.lt'      => 'Giá khuyến mãi phải nhỏ hơn giá gốc',

            'image.image' => 'File tải lên phải là hình ảnh',
            'image.mimes' => 'Hình ảnh chỉ chấp nhận định dạng: jpg, jpeg, png, webp',
            'image.max'   => 'Dung lượng hình ảnh tối đa là 2MB',

            'combo_items.required' => 'Vui lòng chọn dịch vụ cho combo',
            'combo_items.array'    => 'Danh sách combo không hợp lệ',

            'combo_items.*.service_id.required' => 'Dịch vụ trong combo không hợp lệ',
            'combo_items.*.service_id.exists'   => 'Dịch vụ trong combo không tồn tại',

            'combo_items.*.quantity.required' => 'Số lượng dịch vụ trong combo là bắt buộc',
            'combo_items.*.quantity.integer'  => 'Số lượng phải là số',
            'combo_items.*.quantity.min'      => 'Số lượng tối thiểu là 1',

            'is_combo.boolean'  => 'Trạng thái combo không hợp lệ',
            'is_active.boolean' => 'Trạng thái không hợp lệ',
        ];
    }
}
