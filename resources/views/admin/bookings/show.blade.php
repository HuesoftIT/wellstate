@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Booking #{{ $booking->booking_code }}
@endsection

@section('contentheader_title')
    Booking #{{ $booking->booking_code }}
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li>
            <a href="{{ url('admin') }}">
                <i class="fa fa-home"></i> {{ __('message.dashboard') }}
            </a>
        </li>
        <li>
            <a href="{{ route('bookings.index') }}">Bookings</a>
        </li>
        <li class="active">{{ __('message.detail') }}</li>
    </ol>
@endsection

@section('main-content')
    <div class="box">

        {{-- HEADER --}}
        <div class="box-header">
            <h5 class="float-left">{{ __('message.detail') }}</h5>

            <div class="box-tools">

                <a href="{{ route('bookings.index') }}" class="btn btn-default btn-sm mr-1">
                    <i class="fa fa-arrow-left"></i> {{ __('message.lists') }}
                </a>

                {{-- CONFIRM PAYMENT --}}
                @can('BookingController@confirmPayment')
                    @if ($booking->payment_status === 'unpaid' && in_array($booking->status, ['pending', 'confirmed']))
                        <form action="{{ route('bookings.confirm-payment', $booking->id) }}" method="POST"
                            style="display:inline" onsubmit="return confirm('Xác nhận booking này đã thanh toán?')">
                            @csrf
                            @method('PATCH')

                            <button class="btn btn-success btn-sm mr-1">
                                <i class="fas fa-credit-card"></i> Xác nhận thanh toán
                            </button>
                        </form>
                    @endif
                @endcan

                {{-- CONFIRM BOOKING --}}
                @can('BookingController@confirm')
                    @if ($booking->status === 'pending')
                        <form action="{{ route('bookings.confirm', $booking->id) }}" method="POST" style="display:inline"
                            onsubmit="return confirm('Xác nhận booking này?')">
                            @csrf
                            @method('PATCH')

                            <button class="btn btn-primary btn-sm mr-1">
                                <i class="fas fa-check"></i> Xác nhận booking
                            </button>
                        </form>
                    @endif
                @endcan

                {{-- COMPLETE --}}
                @can('BookingController@update')
                    @if ($booking->status === 'confirmed' && $booking->payment_status === 'paid')
                        <form action="{{ route('bookings.update', $booking->id) }}" method="POST" style="display:inline"
                            onsubmit="return confirm('Đánh dấu booking này là hoàn thành?')">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="status" value="completed">
                            <input type="hidden" name="payment_status" value="paid">

                            <button class="btn btn-info btn-sm mr-1">
                                <i class="fas fa-flag-checkered"></i> Hoàn thành
                            </button>
                        </form>
                    @endif
                @endcan

                {{-- CANCEL --}}
                @can('BookingController@destroy')
                    @if (!in_array($booking->status, ['completed', 'cancelled']))
                        <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST" style="display:inline"
                            onsubmit="return confirm('Bạn chắc chắn muốn huỷ booking này?')">
                            @csrf
                            @method('PATCH')

                            <button class="btn btn-danger btn-sm">
                                <i class="fas fa-times"></i> Huỷ booking
                            </button>
                        </form>
                    @endif
                @endcan

            </div>

        </div>

        {{-- BASIC INFO --}}
        <div class="box-body table-responsive no-padding">
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <th width="25%">Mã booking</th>
                        <td><strong>{{ $booking->booking_code }}</strong></td>
                    </tr>

                    <tr>
                        <th>Người đặt</th>
                        <td>
                            {{ $booking->booker_name }} <br>
                            <small class="text-muted">{{ $booking->booker_phone }}</small>
                        </td>
                    </tr>

                    @auth('customer')
                        <tr>
                            <th>Khách hàng</th>
                            <td>{{ optional($booking->customer)->name ?? '-' }}</td>
                        </tr>
                    @endauth

                    <tr>
                        <th>Chi nhánh</th>
                        <td>{{ optional($booking->branch)->name ?? '-' }}</td>
                    </tr>


                    <tr>
                        <th>Loại phòng</th>
                        <td>{{ optional($booking->branchRoomType)->roomType->name ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Phí phòng</th>
                        <td>
                            @if (optional($booking->branchRoomType)->price)
                                <strong class="text-danger">
                                    {{ number_format($booking->branchRoomType->price, 0, ',', '.') }} ₫
                                </strong>
                            @else
                                <span class="text-muted">0 ₫</span>
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Ngày booking</th>
                        <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}</td>
                    </tr>

                    <tr>
                        <th>Thời gian</th>
                        <td>{{ $booking->start_time }} - {{ $booking->end_time }}</td>
                    </tr>

                    <tr>
                        <th>Số khách</th>
                        <td>{{ $booking->total_guests }}</td>
                    </tr>

                    <tr>
                        <th>Tổng tiền</th>
                        <td class="text-success text-bold">
                            {{ number_format($booking->total_amount) }} đ
                        </td>
                    </tr>

                    <tr>
                        <th>Trạng thái</th>
                        {{-- STATUS --}}
                        <td >
                            @php
                                $statusMap = [
                                    'pending' => ['class' => 'label-warning', 'text' => 'Chờ xác nhận'],
                                    'confirmed' => ['class' => 'label-success', 'text' => 'Đã xác nhận'],
                                    'cancelled' => ['class' => 'label-danger', 'text' => 'Đã huỷ'],
                                    'completed' => ['class' => 'label-primary', 'text' => 'Hoàn thành'],
                                ];

                                $status = $statusMap[$booking->status] ?? [
                                    'class' => 'label-default',
                                    'text' => 'Không xác định',
                                ];
                            @endphp

                            <span class="label {{ $status['class'] }}">
                                {{ $status['text'] }}
                            </span>
                        </td>

                    </tr>

                    <tr>
                        <th>Thanh toán</th>
                        <td>
                            {!! $booking->payment_status === 'paid'
                                ? '<span class="label label-success">Đã thanh toán</span>'
                                : '<span class="label label-default">Chưa thanh toán</span>' !!}
                        </td>
                    </tr>

                    <tr>
                        <th>Ghi chú</th>
                        <td>{{ $booking->note ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Tạo lúc</th>
                        <td>{{ $booking->created_at->format(config('settings.format.datetime')) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- GUESTS & SERVICES --}}
        @if ($booking->guests->count())
            <div class="box">
                <div class="box-header">
                    <h5>Danh sách khách & dịch vụ</h5>
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Khách</th>
                                <th>Dịch vụ</th>
                                <th>Thời lượng</th>
                                <th>Giá</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($booking->guests as $gIndex => $guest)
                                @foreach ($guest->services as $service)
                                    <tr>
                                        <td>{{ $gIndex + 1 }}</td>
                                        <td>{{ $guest->guest_name ?? 'Guest' }}</td>
                                        <td>{{ optional($service->service)->title ?? '-' }}</td>
                                        <td>{{ $service->duration }} phút</td>
                                        <td>{{ number_format($service->price) }} đ</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>
@endsection
