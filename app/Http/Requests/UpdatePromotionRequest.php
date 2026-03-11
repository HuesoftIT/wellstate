<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePromotionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'discount_max_value' => $this->discount_max_value ?: null,
        ]);
    }
    public function rules()
    {
        // Lấy promotion id từ route
        $promotionId = $this->route('promotion')
            ?? $this->route('id');

        return [

            // ===== BASIC INFO =====
            'title' => 'required|string|max:255',

            'apply_scope' => [
                'required',
                Rule::in(['booking', 'service', 'room'])
            ],

            'discount_code' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('promotions', 'discount_code')->ignore($promotionId)
            ],

            'content' => 'nullable|string',

            // ===== IMAGE =====
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',

            // ===== DISCOUNT =====
            'discount_type' => [
                'required',
                Rule::in(['percent', 'fixed'])
            ],

            'discount_value' => 'required|numeric|min:0',

            'discount_min_order_value' => 'nullable|numeric|min:0',

            'discount_max_value' => 'nullable|numeric|min:0',

            'discount_max_uses' => 'nullable|integer|min:1',

            'discount_max_uses_per_user' => 'nullable|integer|min:1',

            // ===== DATE =====
            'start_date' => 'required|date',

            'end_date' => 'nullable|date|after_or_equal:start_date',

            // ===== STATUS =====
            'is_active' => 'boolean',

            'is_auto' => 'boolean',

            // ===== SERVICE RULE =====
            'service_rule' => ['nullable', Rule::in(['all', 'only'])],

            'service_ids' => 'nullable|array|required_if:service_rule,only',

            'service_ids.*' => 'exists:services,id',

            // ===== MEMBERSHIP RULE =====
            'membership_all' => 'nullable|boolean',

            'membership_levels' => 'nullable|array',

            'membership_levels.*' => 'exists:memberships,id',

            // ===== USER RULE =====
            'user_rule' => ['nullable', Rule::in(['all', 'only'])],

            'user_ids' => 'nullable|array|required_if:user_rule,only',

            'user_ids.*' => 'exists:customers,id',
        ];
    }

    /**
     * Validate động theo discount_type
     */
    public function withValidator($validator)
    {
        $validator->sometimes(
            'discount_value',
            'max:100',
            function () {
                return $this->input('discount_type') === 'percent';
            }
        );
    }

    public function messages()
    {
        return [

            'title.required' => 'Tên khuyến mãi là bắt buộc',

            'apply_scope.required' => 'Vui lòng chọn phạm vi áp dụng',
            'apply_scope.in' => 'Phạm vi áp dụng không hợp lệ',

            'discount_code.unique' => 'Mã khuyến mãi đã tồn tại',

            'image.image' => 'Ảnh phải là file hình hợp lệ',

            'discount_type.required' => 'Loại giảm giá là bắt buộc',

            'discount_value.required' => 'Giá trị giảm giá là bắt buộc',

            'discount_value.max' => 'Giảm theo % không được vượt quá 100%',

            'start_date.required' => 'Ngày bắt đầu là bắt buộc',

            'end_date.after_or_equal' => 'Ngày kết thúc phải >= ngày bắt đầu',

            'service_ids.required_if' => 'Vui lòng chọn ít nhất 1 dịch vụ',

            'membership_levels.required_if' => 'Vui lòng chọn hạng thành viên',

            'user_ids.required_if' => 'Vui lòng chọn người dùng áp dụng',

            'user_ids.*.exists' => 'Khách hàng không tồn tại',
        ];
    }
}
