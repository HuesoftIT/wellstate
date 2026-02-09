<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $postId = $this->route('post');

        return [
            'title' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('posts')
                    ->ignore($postId)
                    ->whereNull('deleted_at'),
            ],
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'post_category_id' => 'required|exists:post_categories,id',
            'is_active' => 'boolean',
            'published_at' => 'nullable|date',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Vui lòng nhập tiêu đề bài viết',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự',

            'slug.unique' => 'Slug đã tồn tại',
            'slug.max' => 'Slug không được vượt quá 255 ký tự',

            'excerpt.max' => 'Mô tả ngắn không được vượt quá 500 ký tự',

            'content.required' => 'Vui lòng nhập nội dung bài viết',

            'post_category_id.required' => 'Vui lòng chọn danh mục bài viết',
            'post_category_id.exists' => 'Danh mục bài viết không tồn tại',

            'published_at.date' => 'Ngày đăng không hợp lệ',
            'image.image' => 'File tải lên phải là hình ảnh',
            'image.mimes' => 'Hình ảnh chỉ chấp nhận định dạng: jpg, jpeg, png, webp',
            'image.max'   => 'Dung lượng hình ảnh tối đa là 2MB',
        ];
    }
}
