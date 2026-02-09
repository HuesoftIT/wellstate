<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreImageCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'      => 'required|string|max:255|unique:image_categories,name',
            'is_active' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên danh mục không được để trống',
            'name.unique'   => 'Tên danh mục đã tồn tại',
            'name.max'      => 'Tên danh mục không quá 255 ký tự',


            'is_active.required' => 'Vui lòng chọn trạng thái',
            'is_active.boolean'  => 'Trạng thái không hợp lệ',
        ];
    }
}
