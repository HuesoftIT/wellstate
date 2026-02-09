<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $branchId = $this->route('branch');

        return [

            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'note' => 'nullable|string',
            'is_active' => 'boolean',
            'open_time' => 'nullable|date_format:H:i',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'close_time' => 'nullable|date_format:H:i|after:open_time',
        ];
    }

    public function messages(): array
    {
        return [
            // LAT / LNG
            'latitude.numeric' => 'Vĩ độ phải là số',
            'latitude.between' => 'Vĩ độ phải nằm trong khoảng -90 đến 90',
            'longitude.numeric' => 'Kinh độ phải là số',
            'longitude.between' => 'Kinh độ phải nằm trong khoảng -180 đến 180',

            // TIME
            'open_time.date_format' => 'Giờ mở cửa phải đúng định dạng HH:mm',
            'close_time.date_format' => 'Giờ đóng cửa phải đúng định dạng HH:mm',
            'close_time.after' => 'Giờ đóng cửa phải sau giờ mở cửa',
            'name.required' => 'Vui lòng nhập tên chi nhánh',
            'name.max' => 'Tên chi nhánh không vượt quá :max ký tự',

            'email.email' => 'Email không đúng định dạng',
            'phone.max' => 'Số điện thoại không vượt quá :max ký tự',
            'image.image' => 'File tải lên phải là hình ảnh',
            'image.mimes' => 'Hình ảnh chỉ chấp nhận định dạng: jpg, jpeg, png, webp',
            'image.max'   => 'Dung lượng hình ảnh tối đa là 2MB',
        ];
    }
}
