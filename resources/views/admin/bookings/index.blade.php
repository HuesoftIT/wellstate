@extends('adminlte::layouts.app')
@section('style')
    <style>
        .select2 {
            width: 250px;
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
                        <th>Người đặt</th>
                        <th>Chi nhánh</th>
                        <th>Thời gian</th>
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
                                <strong class="text-primary">
                                    {{ $item->booking_code }}
                                </strong>
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

                            {{-- TIME --}}
                            <td>
                                <div>
                                    {{ \Carbon\Carbon::parse($item->booking_date)->format('d/m/Y') }}
                                </div>
                                <small class="text-muted">
                                    {{ $item->start_time }} - {{ $item->end_time }}
                                </small>
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
                                @php
                                    if ($item->status === 'pending') {
                                        $statusClass = 'label-warning';
                                    } elseif ($item->status === 'confirmed') {
                                        $statusClass = 'label-success';
                                    } elseif ($item->status === 'cancelled') {
                                        $statusClass = 'label-danger';
                                    } elseif ($item->status === 'completed') {
                                        $statusClass = 'label-primary';
                                    } else {
                                        $statusClass = 'label-default';
                                    }
                                @endphp

                                <span class="label {{ $statusClass }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>

                            {{-- PAYMENT --}}
                            <td class="text-center">
                                @if ($item->payment_status === 'paid')
                                    <span class="label label-success">Đã thanh toán</span>
                                @else
                                    <span class="label label-default">Chưa thanh toán</span>
                                @endif
                            </td>

                            {{-- CREATED --}}
                            <td>
                                {{ $item->created_at->format(config('settings.format.date')) }}
                            </td>

                            {{-- ACTION --}}
                            <td class="dropdown">
                                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                    <i class="fal fa-tools"></i>
                                </button>

                                <div class="dropdown-menu p-0">
                                    @can('BookingController@show')
                                        <a href="{{ route('bookings.show', $item->id) }}"
                                            class="btn btn-info btn-sm dropdown-item">
                                            <i class="fas fa-eye"></i> {{ __('message.view') }}
                                        </a>
                                    @endcan

                                    @can('BookingController@update')
                                        <a href="{{ route('bookings.edit', $item->id) }}"
                                            class="btn btn-primary btn-sm dropdown-item">
                                            <i class="far fa-edit"></i> {{ __('message.edit') }}
                                        </a>
                                    @endcan

                                    @can('BookingController@destroy')
                                        <form action="{{ route('bookings.cancel', $item->id) }}" method="POST"
                                            style="display:inline"
                                            onsubmit="return confirm('Bạn chắc chắn muốn huỷ booking này?')">

                                            @csrf
                                            @method('PATCH')

                                            <button type="submit" class="btn btn-danger btn-sm dropdown-item">
                                                <i class="fas fa-times"></i> Huỷ
                                            </button>
                                        </form>
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
                    <a href="#" id="activeBookings" data-action="activeBookings" class="btn-act btn btn-success btn-sm"
                        title="{{ __('message.approved') }}">
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
