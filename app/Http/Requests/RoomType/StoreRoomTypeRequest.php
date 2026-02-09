<?php

namespace App\Http\Requests\RoomType;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'       => 'required|string|max:191',
            'capacity'   => 'required|integer|min:1',
            'extra_fee'  => 'nullable|numeric|min:0',
            'is_active'  => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute không được để trống.',
            'unique'   => ':attribute đã tồn tại.',
            'integer'  => ':attribute phải là số nguyên.',
            'numeric'  => ':attribute phải là số.',
            'min'      => ':attribute phải lớn hơn hoặc bằng :min.',
            'boolean'  => ':attribute không hợp lệ.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name'      => 'Tên loại phòng',
            'capacity'  => 'Sức chứa',
            'extra_fee' => 'Phụ phí',
            'is_active' => 'Trạng thái',
        ];
    }
}
