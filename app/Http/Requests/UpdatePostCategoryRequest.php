<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostCategoryRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('post_categories')
                    ->ignore($this->route('post_category'))
                    ->whereNull('deleted_at'),
            ],
            'description' => 'nullable|string',
            'is_active'   => 'required|boolean',
            'order'       => 'nullable|integer|min:0',
            'parent_id' => [
                'nullable',
                'integer',
                Rule::exists('post_categories', 'id')
                    ->whereNull('deleted_at'),
            ],
        ];
    }


    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên danh mục bài viết.',
            'name.string'   => 'Tên danh mục bài viết phải là chuỗi ký tự.',
            'name.max'      => 'Tên danh mục bài viết không được vượt quá 255 ký tự.',

            'slug.string' => 'Slug phải là chuỗi ký tự.',
            'slug.max'    => 'Slug không được vượt quá 255 ký tự.',
            'slug.unique' => 'Slug đã tồn tại, vui lòng chọn slug khác.',

            'description.string' => 'Mô tả danh mục phải là chuỗi ký tự.',

            'is_active.required' => 'Vui lòng chọn trạng thái hiển thị.',
            'is_active.boolean'  => 'Trạng thái hiển thị không hợp lệ.',

            'order.integer' => 'Thứ tự hiển thị phải là số nguyên.',
            'order.min'     => 'Thứ tự hiển thị phải lớn hơn hoặc bằng 0.',
            'parent_id.integer' => 'Danh mục cha không hợp lệ.',
            'parent_id.exists'  => 'Danh mục cha không tồn tại hoặc đã bị xoá.',
        ];
    }
}
