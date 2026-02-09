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

    public function rules()
    {
        // Lấy promotion id từ route
        $promotionId = $this->route('promotion')
            ?? $this->route('id');

        return [
            // ===== BASIC INFO =====
            'type' => ['required', Rule::in(['promotion', 'membership'])],
            'title' => 'required|string|max:255',

            'discount_code' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('promotions', 'discount_code')->ignore($promotionId),
            ],

            'description' => 'nullable|string',

            // ===== IMAGE =====
            // Update KHÔNG bắt buộc upload lại ảnh
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',

            // ===== DISCOUNT =====
            'discount_type'  => ['required', Rule::in(['percent', 'fixed'])],
            'discount_value' => 'required|numeric|min:0',
            'discount_min_order_value' => 'required|numeric|min:0',

            'discount_max_value' => 'nullable|numeric|min:0',
            'discount_max_uses' => 'nullable|integer|min:1',
            'discount_max_uses_per_user' => 'nullable|integer|min:1',

            // ===== DATE =====
            'start_date' => 'required|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',

            // ===== STATUS =====
            'is_active' => 'boolean',

            // ===== SERVICE RULE =====
            'service_rule' => ['nullable', Rule::in(['all', 'only'])],
            'service_ids' => 'nullable|array|required_if:service_rule,only',
            'service_ids.*' => 'exists:services,id',

            // ===== MEMBERSHIP RULE =====
            'membership_all' => 'nullable|boolean',
            'membership_levels' => 'nullable|array|required_if:type,membership',
            'membership_levels.*' => 'exists:memberships,id',

            // ===== USER RULE =====p
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
            'type.required' => 'Loại khuyến mãi là bắt buộc',
            'type.in' => 'Loại khuyến mãi không hợp lệ',

            'title.required' => 'Tên khuyến mãi là bắt buộc',

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
