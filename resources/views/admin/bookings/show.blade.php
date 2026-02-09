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

                @can('BookingController@update')
                    <a href="{{ route('bookings.edit', $booking->id) }}" class="btn btn-default btn-sm mr-1">
                        <i class="far fa-edit"></i> {{ __('message.edit') }}
                    </a>
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
                        <td>
                            @php
                                if ($booking->status === 'pending') {
                                    $statusClass = 'label-warning';
                                } elseif ($booking->status === 'confirmed') {
                                    $statusClass = 'label-success';
                                } elseif ($booking->status === 'cancelled') {
                                    $statusClass = 'label-danger';
                                } elseif ($booking->status === 'completed') {
                                    $statusClass = 'label-primary';
                                } else {
                                    $statusClass = 'label-default';
                                }
                            @endphp

                            <span class="label {{ $statusClass }}">
                                {{ ucfirst($booking->status) }}
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
                                <th>Phòng</th>
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
                                        <td>{{ optional($service->room)->name ?? '-' }}</td>
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
