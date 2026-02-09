<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkingShiftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'branch_id'  => 'required|exists:branches,id',
            'name'       => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i|after:start_time',
            'is_active'  => 'nullable|boolean',
        ];
    }

    /**
     * Custom validation messages
     */
    public function messages(): array
    {
        return [
            'branch_id.required'  => __('Vui lòng chọn chi nhánh'),
            'branch_id.exists'   => __('Chi nhánh không tồn tại'),

            'name.required'      => __('Vui lòng nhập tên ca làm việc'),
            'name.string'        => __('Tên ca làm việc không hợp lệ'),
            'name.max'           => __('Tên ca làm việc không được vượt quá :max ký tự'),

            'start_time.required' => __('Vui lòng nhập giờ bắt đầu'),
            'start_time.date_format'
            => __('Giờ bắt đầu không đúng định dạng (HH:mm)'),

            'end_time.required'  => __('Vui lòng nhập giờ kết thúc'),
            'end_time.date_format'
            => __('Giờ kết thúc không đúng định dạng (HH:mm)'),
            'end_time.after'     => __('Giờ kết thúc phải lớn hơn giờ bắt đầu'),

            'is_active.boolean'  => __('Trạng thái không hợp lệ'),
        ];
    }

    /**
     * Custom attribute names
     */
    public function attributes(): array
    {
        return [
            'branch_id'  => __('Chi nhánh'),
            'name'       => __('Tên ca làm việc'),
            'start_time' => __('Giờ bắt đầu'),
            'end_time'   => __('Giờ kết thúc'),
            'is_active'  => __('Trạng thái'),
        ];
    }
}
