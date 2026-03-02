<?php

namespace App\Http\Requests;

use App\Models\BranchRoomType;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'booker_email'     => 'required|email|max:255',
            'booker_phone'     => 'required|string|max:20',

            'guest_count'      => 'required|integer|min:1',

            'branch_id'        => 'required|exists:branches,id',
            'room_type_id'     => 'required|exists:branch_room_types,room_type_id',

            'booking_date'     => 'required|date_format:d/m/Y',
            'booking_time'     => 'required|date_format:H:i',

            'discount_code'    => 'nullable|string',

            // Guests
            'guests'                       => 'required|array|min:1',
            'guests.*.uid'                 => 'required|string',
            'guests.*.name'                => 'required|string|max:255',

            // Services inside guest
            'guests.*.services'                          => 'required|array|min:1',
            'guests.*.services.*.service_category_id'    => 'required|exists:service_categories,id',
            'guests.*.services.*.service_id'             => 'required|exists:services,id',
            'guests.*.services.*.price'                  => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [

            // Booker
            'booker_email.required' => 'Vui lòng nhập email người đặt.',
            'booker_email.email'   => 'Email người đặt không hợp lệ.',
            'booker_email.max'      => 'Email người đặt không được vượt quá 255 ký tự.',

            'booker_phone.required' => 'Vui lòng nhập số điện thoại.',
            'booker_phone.string'   => 'Số điện thoại không hợp lệ.',
            'booker_phone.max'      => 'Số điện thoại không được vượt quá 20 ký tự.',

            // Guest count
            'guest_count.required' => 'Vui lòng nhập số lượng khách.',
            'guest_count.integer'  => 'Số lượng khách phải là số nguyên.',
            'guest_count.min'      => 'Số lượng khách phải lớn hơn 0.',

            // Branch & room
            'branch_id.required' => 'Vui lòng chọn chi nhánh.',
            'branch_id.exists'   => 'Chi nhánh không tồn tại.',

            'room_type_id.required' => 'Vui lòng chọn loại phòng.',
            'room_type_id.exists'   => 'Loại phòng không tồn tại.',

            // Date time
            'booking_date.required'    => 'Vui lòng chọn ngày đặt.',
            'booking_date.date_format' => 'Ngày phải đúng định dạng dd/mm/YYYY.',

            'booking_time.required'    => 'Vui lòng chọn giờ đặt.',
            'booking_time.date_format' => 'Giờ phải đúng định dạng HH:mm.',

            // Guests
            'guests.required' => 'Phải có ít nhất một khách.',
            'guests.array'    => 'Dữ liệu khách không hợp lệ.',
            'guests.min'      => 'Phải có ít nhất một khách.',

            'guests.*.uid.required'  => 'Thiếu mã định danh khách.',
            'guests.*.name.required' => 'Vui lòng nhập tên khách.',
            'guests.*.name.max'      => 'Tên khách không được vượt quá 255 ký tự.',

            // Services
            'guests.*.services.required' => 'Mỗi khách phải chọn ít nhất một dịch vụ.',
            'guests.*.services.array'    => 'Dữ liệu dịch vụ không hợp lệ.',
            'guests.*.services.min'      => 'Mỗi khách phải có ít nhất một dịch vụ.',

            'guests.*.services.*.service_category_id.required' => 'Thiếu danh mục dịch vụ.',
            'guests.*.services.*.service_category_id.exists'   => 'Danh mục dịch vụ không tồn tại.',

            'guests.*.services.*.service_id.required' => 'Vui lòng chọn dịch vụ.',
            'guests.*.services.*.service_id.exists'   => 'Dịch vụ không tồn tại.',

            'guests.*.services.*.price.required' => 'Thiếu giá dịch vụ.',
            'guests.*.services.*.price.numeric'  => 'Giá dịch vụ phải là số.',
            'guests.*.services.*.price.min'      => 'Giá dịch vụ không được nhỏ hơn 0.',
        ];
    }

    public function attributes()
    {
        return [
            'booker_email' => 'email người đặt',
            'booker_phone' => 'số điện thoại',
            'guest_count' => 'số lượng khách',
            'branch_id' => 'chi nhánh',
            'room_type_id' => 'loại phòng',
            'booking_date' => 'ngày đặt',
            'booking_time' => 'giờ đặt',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            if ($this->guest_count && count($this->guests ?? []) != $this->guest_count) {
                $validator->errors()->add(
                    'guest_count',
                    'Số lượng khách không khớp với dữ liệu gửi lên.'
                );
            }
        });

        $validator->after(function ($validator) {

            if ($this->booking_date && $this->booking_time) {
                $datetime = Carbon::createFromFormat(
                    'd/m/Y H:i',
                    $this->booking_date . ' ' . $this->booking_time
                );

                if ($datetime->isPast()) {
                    $validator->errors()->add(
                        'booking_time',
                        'Thời gian bạn chọn đã qua. Vui lòng chọn một thời điểm hợp lệ.'
                    );
                }
            }
        });

        $room = BranchRoomType::where('branch_id', $this->branch_id)
            ->where('room_type_id', $this->room_type_id)
            ->first();

        if ($room && $this->guest_count > $room->capacity) {
            $validator->errors()->add(
                'guest_count',
                'Số lượng khách vượt quá sức chứa phòng.'
            );
        }
    }
}
