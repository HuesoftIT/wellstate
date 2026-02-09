<?php

namespace App\Http\Requests\BranchRoomType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBranchRoomTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'branch_id' => [
                'required',
                'exists:branches,id',
                Rule::unique('branch_room_types')
                    ->where(function ($query) {
                        return $query->where('room_type_id', $this->room_type_id);
                    }),
            ],

            'room_type_id' => [
                'required',
                'exists:room_types,id',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'capacity' => [
                'required',
                'integer',
                'min:1',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'branch_id.required'     => 'Vui lòng chọn chi nhánh',
            'branch_id.exists'       => 'Chi nhánh không tồn tại',
            'branch_id.unique'       => 'Loại phòng này đã tồn tại trong chi nhánh đã chọn',

            'room_type_id.required' => 'Vui lòng chọn loại phòng',
            'room_type_id.exists'   => 'Loại phòng không tồn tại',

            'price.required'        => 'Vui lòng nhập giá phòng',
            'price.numeric'         => 'Giá phòng phải là số',
            'price.min'             => 'Giá phòng phải lớn hơn hoặc bằng 0',

            'capacity.required'     => 'Vui lòng nhập sức chứa',
            'capacity.integer'      => 'Sức chứa phải là số nguyên',
            'capacity.min'          => 'Sức chứa tối thiểu là 1',
        ];
    }

    public function attributes(): array
    {
        return [
            'branch_id'     => 'Chi nhánh',
            'room_type_id' => 'Loại phòng',
            'price'        => 'Giá phòng',
            'capacity'     => 'Sức chứa',
            'is_active'    => 'Trạng thái',
        ];
    }
}
