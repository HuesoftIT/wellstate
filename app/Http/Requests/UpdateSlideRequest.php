<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSlideRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:255',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

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
            'image.max'          => 'Đường dẫn ảnh không hợp lệ',
            'image.image' => 'File tải lên phải là hình ảnh',
            'image.mimes' => 'Hình ảnh chỉ chấp nhận định dạng: jpg, jpeg, png, webp',
            'image.max'   => 'Dung lượng hình ảnh tối đa là 2MB',
            'is_active.required' => 'Vui lòng chọn trạng thái',
        ];
    }
}
