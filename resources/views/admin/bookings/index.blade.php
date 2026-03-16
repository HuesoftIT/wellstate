@extends('adminlte::layouts.app')
@section('style')
    <style>
        .select2 {
            width: 250px;
        }

        .small-box {
            cursor: pointer;
            transition: 0.2s;
        }

        .small-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }
    </style>
@endsection
@section('htmlheader_title')
    {{ __('bookings.bookings') }}
@endsection
@section('contentheader_title')
    {{ __('bookings.bookings') }}
@endsection
@section('contentheader_description')
@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url('admin') }}"><i class="fa fa-home"></i> {{ __('message.dashboard') }}</a></li>
        <li class="active">{{ __('bookings.bookings') }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="content-header border-bottom pb-5">
            <h5 class="float-left">
                {{ __('message.lists') }}
            </h5>
            @can('BookingController@store')
                <a href="{{ url('/admin/bookings/create') }}" class="btn btn-default float-right">
                    <i class="fa fa-plus-circle"></i> {{ __('message.new_add') }}
                </a>
            @endcan

        </div>
        <div class="row mb-3">

            {{-- BOOKING HÔM NAY --}}
            <div class="col-md-2">
                <a href="{{ route('bookings.index', ['booking_date' => now()->format('Y-m-d')]) }}">
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>{{ $stats['today_bookings'] }}</h3>
                            <p>Booking hôm nay</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-calendar-day"></i>
                        </div>
                    </div>
                </a>
            </div>


            {{-- HÔM NAY CHƯA THANH TOÁN --}}
            <div class="col-md-2">
                <a
                    href="{{ route('bookings.index', [
                        'booking_date' => now()->format('Y-m-d'),
                        'payment_status' => \App\Models\Booking::PAYMENT_UNPAID,
                    ]) }}">
                    <div class="small-box bg-maroon">
                        <div class="inner">
                            <h3>{{ $stats['today_unpaid'] }}</h3>
                            <p>Hôm nay chưa thanh toán</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-exclamation-triangle"></i>
                        </div>
                    </div>
                </a>
            </div>


            {{-- CHỜ XÁC NHẬN --}}
            <div class="col-md-2">
                <a href="{{ route('bookings.index', ['status' => \App\Models\Booking::STATUS_PENDING]) }}">
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3>{{ $stats['pending'] }}</h3>
                            <p>Chờ xác nhận</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-clock"></i>
                        </div>
                    </div>
                </a>
            </div>


            {{-- CHƯA THANH TOÁN --}}
            <div class="col-md-2">
                <a href="{{ route('bookings.index', ['payment_status' => \App\Models\Booking::PAYMENT_UNPAID]) }}">
                    <div class="small-box bg-red">
                        <div class="inner">
                            <h3>{{ $stats['unpaid'] }}</h3>
                            <p>Tổng chưa thanh toán</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-credit-card"></i>
                        </div>
                    </div>
                </a>
            </div>


            {{-- ĐANG DIỄN RA --}}
            <div class="col-md-2">
                <a href="{{ route('bookings.index', ['filter' => 'running']) }}">
                    <div class="small-box bg-blue">
                        <div class="inner">
                            <h3>{{ $stats['running'] }}</h3>
                            <p>Đang diễn ra</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-play"></i>
                        </div>
                    </div>
                </a>
            </div>


            {{-- SẮP DIỄN RA --}}
            <div class="col-md-2">
                <a href="{{ route('bookings.index', ['filter' => 'upcoming']) }}">
                    <div class="small-box bg-purple">
                        <div class="inner">
                            <h3>{{ $stats['upcoming'] }}</h3>
                            <p>Sắp diễn ra</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-hourglass-half"></i>
                        </div>
                    </div>
                </a>
            </div>

        </div>
        <div class="box-header">
            <div class="box-tools">
                {!! Form::open([
                    'method' => 'GET',
                    'route' => 'bookings.index',
                    'class' => 'pull-left',
                ]) !!}

                <div class="input-group" style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">

                    {{-- SEARCH --}}
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control input-sm"
                        placeholder="Mã booking / Tên / SĐT" style="width:220px">

                    {{-- BRANCH --}}
                    {!! Form::select('branch_id', $branches, request('branch_id'), [
                        'class' => 'form-control input-sm',
                        'placeholder' => __('bookings.branch'),
                        'style' => 'width:180px',
                    ]) !!}

                    {{-- BOOKING DATE --}}
                    <input type="date" name="booking_date" value="{{ request('booking_date') }}"
                        class="form-control input-sm" style="width:160px">

                    {{-- STATUS --}}
                    {!! Form::select(
                        'status',
                        [
                            'pending' => __('bookings.status_pending'),
                            'confirmed' => __('bookings.status_confirmed'),
                            'completed' => __('bookings.status_completed'),
                            'cancelled' => __('bookings.status_cancelled'),
                        ],
                        request('status'),
                        [
                            'class' => 'form-control input-sm',
                            'placeholder' => __('bookings.status'),
                            'style' => 'width:160px',
                        ],
                    ) !!}

                    {{-- PAYMENT STATUS --}}
                    {!! Form::select(
                        'payment_status',
                        [
                            'paid' => __('bookings.payment_paid'),
                            'unpaid' => __('bookings.payment_unpaid'),
                        ],
                        request('payment_status'),
                        [
                            'class' => 'form-control input-sm',
                            'placeholder' => __('bookings.payment_status'),
                            'style' => 'width:160px',
                        ],
                    ) !!}

                    <button class="btn btn-secondary btn-sm" type="submit">
                        <i class="fa fa-search"></i> {{ __('message.search') }}
                    </button>

                </div>

                {!! Form::close() !!}
            </div>
        </div>

        @php
            $index = ($bookings->currentPage() - 1) * $bookings->perPage();
        @endphp

        <div class="box-body no-padding">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center" width="3%">
                            <input type="checkbox" id="chkAll">
                        </th>
                        <th class="text-center" width="4%">{{ __('message.index') }}</th>

                        <th>Mã booking</th>
                        <th>Tên người đặt</th>
                        <th>Chi nhánh</th>
                        <th> @sortablelink('booking_date', 'Thời gian')</th>
                        <th class="text-center">Số khách</th>
                        <th class="text-right">Tổng tiền</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-center">Thanh toán</th>
                        <th>{{ __('message.created_at') }}</th>
                        <th width="7%"></th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($bookings as $index => $item)
                        <tr>
                            {{-- CHECKBOX --}}
                            <td class="text-center">
                                <input type="checkbox" name="chkId" value="{{ $item->id }}">
                            </td>

                            {{-- INDEX --}}
                            <td class="text-center">{{ $index + 1 }}</td>

                            {{-- BOOKING CODE --}}
                            <td>
                                <a href="{{ route('bookings.show', $item->id) }}" class="text-primary text-bold">
                                    {{ $item->booking_code }}
                                </a>
                            </td>

                            {{-- BOOKER --}}
                            <td>
                                <div class="font-weight-bold text-dark">
                                    {{ $item->booker_name }}
                                </div>

                                <div class="d-flex align-items-center mt-1 text-muted cursor-pointer"
                                    style="cursor: pointer;">
                                    <span class="text-muted cursor-pointer"
                                        onclick="copyPhone('{{ $item->booker_phone }}')">
                                        <i class="fa fa-copy"></i> {{ $item->booker_phone }}
                                    </span>


                                </div>
                            </td>


                            {{-- BRANCH --}}
                            <td>
                                {{ optional($item->branch)->name ?? '—' }}
                            </td>

                            <td style="min-width:180px">

                                @php
                                    $bookingStart = \Carbon\Carbon::parse(
                                        $item->booking_date . ' ' . $item->start_time,
                                    );
                                    $bookingEnd = \Carbon\Carbon::parse($item->booking_date . ' ' . $item->end_time);
                                    $now = now();

                                    $isToday = $bookingStart->isToday();
                                    $isTomorrow = $bookingStart->isTomorrow();
                                @endphp

                                <div class="booking-time">

                                    {{-- DATE --}}
                                    <div class="text-bold">
                                        @if ($isToday)
                                            <span class="text-primary">
                                                Hôm nay
                                            </span>
                                        @elseif($isTomorrow)
                                            <span class="text-info">
                                                Ngày mai
                                            </span>
                                        @else
                                            {{ $bookingStart->translatedFormat('l, d/m/Y') }}
                                        @endif
                                    </div>

                                    {{-- TIME --}}
                                    <div class="text-muted">
                                        <i class="fa fa-clock"></i>
                                        {{ $bookingStart->format('H:i') }}
                                        →
                                        {{ $bookingEnd->format('H:i') }}
                                    </div>

                                    {{-- STATUS TIME --}}
                                    <div class="mt-1">

                                        {{-- SẮP DIỄN RA --}}
                                        @if ($now->lt($bookingStart) && $now->diffInMinutes($bookingStart) <= 30)
                                            <span class="label label-warning">
                                                ⏰ Sắp diễn ra
                                            </span>
                                        @endif

                                        {{-- ĐANG DIỄN RA --}}
                                        @if ($now->between($bookingStart, $bookingEnd))
                                            <span class="label label-primary">
                                                🔵 Đang diễn ra
                                            </span>
                                        @endif

                                        {{-- QUÁ GIỜ --}}
                                        @if ($now->gt($bookingEnd) && $item->status !== \App\Models\Booking::STATUS_COMPLETED)
                                            <span class="label label-danger">
                                                ⚠ Quá giờ
                                            </span>
                                        @endif

                                    </div>

                                </div>

                            </td>

                            {{-- GUESTS --}}
                            <td class="text-center">
                                {{ $item->total_guests }}
                            </td>

                            {{-- TOTAL --}}
                            <td class="text-right text-success text-bold">
                                {{ number_format($item->total_amount) }} đ
                            </td>

                            {{-- STATUS --}}
                            <td class="text-center">
                                <span class="label {{ $item->status_info['class'] }}">
                                    {{ $item->status_info['text'] }}
                                </span>
                            </td>


                            {{-- PAYMENT --}}
                            <td class="text-center">
                                <span class="label {{ $item->payment_info['class'] }}">
                                    {{ $item->payment_info['text'] }}
                                </span>
                            </td>

                            {{-- CREATED --}}
                            <td>
                                {{ $item->created_at->format(config('settings.format.date')) }}
                            </td>

                            {{-- ACTION --}}
                            <td class="dropdown text-center">
                                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                    <i class="fal fa-tools"></i>
                                </button>

                                <div class="dropdown-menu p-0">

                                    {{-- VIEW --}}
                                    @can('BookingController@show')
                                        <a href="{{ route('bookings.show', $item->id) }}" class="dropdown-item">
                                            <i class="fas fa-eye text-info"></i> Xem chi tiết
                                        </a>
                                    @endcan


                                    {{-- CONFIRM BOOKING --}}
                                    @can('BookingController@confirm')
                                        @if ($item->canConfirm())
                                            <form action="{{ route('bookings.confirm', $item->id) }}" method="POST"
                                                onsubmit="return confirm('Xác nhận booking này?')">
                                                @csrf
                                                @method('PATCH')

                                                <button type="submit" class="dropdown-item">
                                                    <i class="fas fa-check text-primary"></i> Xác nhận booking
                                                </button>
                                            </form>
                                        @endif
                                    @endcan


                                    {{-- CONFIRM PAYMENT --}}
                                    @can('BookingController@confirmPayment')
                                        @if ($item->canConfirmPayment())
                                            <form action="{{ route('bookings.confirm-payment', $item->id) }}" method="POST"
                                                onsubmit="return confirm('Xác nhận booking này đã thanh toán?')">
                                                @csrf
                                                @method('PATCH')

                                                <button type="submit" class="dropdown-item">
                                                    <i class="fas fa-credit-card text-success"></i> Xác nhận thanh toán
                                                </button>
                                            </form>
                                        @endif
                                    @endcan


                                    {{-- COMPLETE --}}
                                    @can('BookingController@complete')
                                        @if ($item->canComplete())
                                            <form action="{{ route('bookings.complete', $item->id) }}" method="POST"
                                                onsubmit="return confirm('Đánh dấu booking này đã hoàn thành?')">
                                                @csrf
                                                @method('PATCH')

                                                <button type="submit" class="dropdown-item">
                                                    <i class="fas fa-check-circle text-success"></i> Hoàn thành
                                                </button>
                                            </form>
                                        @endif
                                    @endcan


                                    {{-- CANCEL --}}
                                    @can('BookingController@cancel')
                                        @if ($item->canCancel())
                                            <form action="{{ route('bookings.cancel', $item->id) }}" method="POST"
                                                onsubmit="return confirm('Bạn chắc chắn muốn huỷ booking này?')">
                                                @csrf
                                                @method('PATCH')

                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="fas fa-times"></i> Huỷ booking
                                                </button>
                                            </form>
                                        @endif
                                    @endcan

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center text-muted">
                                {{ __('bookings.no_item') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>




        <div class="box-footer clearfix">
            <div id="btn-act">
                @can('BookingController@destroy')
                    <a href="#" id="deleteBookings" data-action="deleteBookings" class="btn-act btn btn-danger btn-sm"
                        title="{{ __('message.delete') }}">
                        <i class="fas fa-trash-alt" aria-hidden="true"></i>
                    </a>
                @endcan
                &nbsp;
                @can('BookingController@active')
                    <a href="#" id="activeBookings" data-action="activeBookings"
                        class="btn-act btn btn-success btn-sm" title="{{ __('message.approved') }}">
                        <i class="fa fa-check" aria-hidden="true"></i>
                    </a>
                @endcan
            </div>
            <div class="page-footer pull-right">
                {!! $bookings->appends(\Request::except('page'))->render() !!}
            </div>
        </div>
    </div>
    @include('sweetalert::alert')
@endsection
@section('scripts-footer')
    @toastr_js
    @toastr_render
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>

    <script type="text/javascript">
        $(function() {
            $('#chkAll').on('click', function() {
                $("input:checkbox").prop('checked', $(this).prop("checked"));
            });
        });
        $('#btn-act').on('click', '.btn-act', function(e) {
            e.preventDefault();
            let action = $(this).data('action');
            console.log(action);
            ajaxCategory(action);
        });

        function ajaxCategory(action) {
            let chkId = $("input[name='chkId']:checked");
            let actTxt = '',
                successAlert = '',
                classAlert = '';
            switch (action) {
                case 'activeBookings':
                    actTxt = 'duyệt';
                    classAlert = 'alert-success';
                    break;
                case 'deleteBookings':
                    actTxt = 'xóa';
                    classAlert = 'alert-danger';
                    break;
            }
            if (chkId.length != 0) {
                swal({
                        title: 'Bạn có muốn ' + actTxt +
                            ' booking này không?',
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            var arrId = '';
                            $("input[name='chkId']:checked").map((val, key) => {
                                arrId += key.value + ',';
                            });
                            axios.get('{{ url('/ajax') }}/' + action, {
                                    params: {
                                        ids: arrId
                                    }
                                })
                                .then((response) => {
                                    if (response.data.success === 'ok') {
                                        location.reload(true);
                                    }
                                })
                                .catch((error) => {})
                        }
                    });
            } else {
                swal("Lỗi!", 'Vui lòng chọn booking để ' + actTxt + '!', "error")
            }
        }
    </script>
    <script>
        function copyPhone(phone) {
            navigator.clipboard.writeText(phone).then(() => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Đã copy số điện thoại',
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true
                });
            }).catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Không thể copy số điện thoại'
                });
            });
        }
    </script>
@endsection
