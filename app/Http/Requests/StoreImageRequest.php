<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'image_category_id' => 'required|exists:image_categories,id',

            'images'   => 'required|array|min:1',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',

            'is_active' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'image_category_id.required' => 'Vui lòng chọn danh mục ảnh',
            'image_category_id.exists'   => 'Danh mục ảnh không hợp lệ',

            'images.required' => 'Vui lòng chọn ít nhất một hình ảnh',
            'images.array'    => 'Dữ liệu hình ảnh không hợp lệ',
            'images.min'      => 'Phải chọn ít nhất một hình ảnh',

            'images.*.image'  => 'File tải lên phải là hình ảnh',
            'images.*.mimes'  => 'Hình ảnh chỉ chấp nhận jpg, jpeg, png, webp',
            'images.*.max'    => 'Dung lượng mỗi hình ảnh tối đa 2MB',

            'is_active.required' => 'Vui lòng chọn trạng thái',
            'is_active.boolean'  => 'Trạng thái không hợp lệ',
        ];
    }
}
