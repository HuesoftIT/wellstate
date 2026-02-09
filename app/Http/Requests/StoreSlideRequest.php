<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSlideRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:255',
            'image'       => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',

            'link'        => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'order'       => 'nullable|integer|min:0',
            'is_active'   => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'     => 'Vui lòng nhập tiêu đề slide',
            'title.max'          => 'Tiêu đề tối đa 255 ký tự',

            'image.required'     => 'Vui lòng chọn hình ảnh slide',
            'image.image' => 'File tải lên phải là hình ảnh',
            'image.mimes' => 'Hình ảnh chỉ chấp nhận định dạng: jpg, jpeg, png, webp',
            'image.max'   => 'Dung lượng hình ảnh tối đa là 2MB',

            'order.integer'      => 'Thứ tự phải là số',
            'order.min'          => 'Thứ tự không được nhỏ hơn 0',

            'is_active.required' => 'Vui lòng chọn trạng thái',
            'is_active.boolean'  => 'Trạng thái không hợp lệ',
        ];
    }
}
