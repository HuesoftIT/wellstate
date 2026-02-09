<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostCommentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'post_id'     => 'required|exists:posts,id',
            'customer_id' => 'nullable|exists:customers,id',
            'parent_id'   => 'nullable|exists:post_comments,id',
            'content'     => 'required|string|min:3',
            'is_approved' => 'boolean',
            'is_spam'     => 'boolean',
        ];
    }

    public function messages()
    {
        return [
            'post_id.required' => 'Bài viết không được để trống',
            'post_id.exists'   => 'Bài viết không tồn tại',

            'content.required' => 'Nội dung bình luận không được để trống',
            'content.min'      => 'Nội dung bình luận tối thiểu :min ký tự',

            'parent_id.exists' => 'Bình luận cha không tồn tại',
            'customer_id.exists' => 'Khách hàng không tồn tại',
        ];
    }
}
