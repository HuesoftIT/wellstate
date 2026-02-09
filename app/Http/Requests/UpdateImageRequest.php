<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'image_category_id' => 'required|exists:image_categories,id',
            'title'             => 'required|string|max:255',
            'image'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'link'              => 'nullable|url|max:255',
            'order'             => 'nullable|integer|min:0',
            'is_active'         => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'image_category_id.required' => 'Vui lòng chọn danh mục ảnh',
            'image_category_id.exists'   => 'Danh mục ảnh không hợp lệ',

            'title.required' => 'Vui lòng nhập tiêu đề',
            'title.max'      => 'Tiêu đề không được vượt quá 255 ký tự',

            'image.image'    => 'File tải lên phải là hình ảnh',
            'image.mimes'    => 'Hình ảnh chỉ chấp nhận jpg, jpeg, png, webp',
            'image.max'      => 'Dung lượng hình ảnh tối đa 2MB',

            'link.url'       => 'Đường dẫn không đúng định dạng URL',

            'order.integer'  => 'Thứ tự phải là số',
            'order.min'      => 'Thứ tự phải lớn hơn hoặc bằng 0',

            'is_active.required' => 'Vui lòng chọn trạng thái',
            'is_active.boolean'  => 'Trạng thái không hợp lệ',
        ];
    }
}
