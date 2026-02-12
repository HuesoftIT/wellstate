@extends('adminlte::layouts.app')
@section('style')
    <style>
        .select2 {
            width: 250px;
        }
    </style>
@endsection
@section('htmlheader_title')
    {{ __('promotions.promotions') }}
@endsection
@section('contentheader_title')
    {{ __('promotions.promotions') }}
@endsection
@section('contentheader_description')
@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url('admin') }}"><i class="fa fa-home"></i> {{ __('message.dashboard') }}</a></li>
        <li class="active">{{ __('promotions.promotions') }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="content-header border-bottom pb-5">
            <h5 class="float-left">
                {{ __('message.lists') }}
            </h5>
            @can('PromotionController@store')
                <a href="{{ url('/admin/promotions/create') }}" class="btn btn-default float-right">
                    <i class="fa fa-plus-circle"></i> {{ __('message.new_add') }}
                </a>
            @endcan

        </div>

        <div class="box-header">
            {!! Form::open(['method' => 'GET', 'url' => route('promotions.index'), 'class' => 'pull-left']) !!}
            <div class="input-group" style="display:flex; gap:5px">

                {{-- Search by name --}}
                <input type="text" name="search" value="{{ request('search') }}" class="form-control input-sm"
                    placeholder="{{ __('message.search_keyword') }}" style="width:150px">

                {{-- Promotion Type --}}
                {!! Form::select(
                    'type',
                    ['promotion' => __('promotions.promotion'), 'membership' => __('promotions.membership')],
                    request('type'),
                    [
                        'class' => 'form-control input-sm select2',
                        'placeholder' => __('promotions.type'),
                    ],
                ) !!}

                {{-- Status --}}
                {!! Form::select(
                    'status',
                    [
                        'active' => 'Đang áp dụng',
                        'upcoming' => 'Sắp diễn ra',
                        'expired' => 'Hết hạn',
                        'disabled' => 'Tắt',
                    ],
                    request('status'),
                    ['class' => 'form-control input-sm', 'placeholder' => __('message.status')],
                ) !!}


                <button class="btn btn-secondary btn-sm" type="submit">
                    <i class="fa fa-search"></i> {{ __('message.search') }}
                </button>
                <a href="{{ route('promotions.index') }}" class="btn btn-default btn-sm">
                    <i class="fa fa-refresh"></i>Làm mới
                </a>

            </div>
            {!! Form::close() !!}
        </div>

        @php($index = ($promotions->currentPage() - 1) * $promotions->perPage())

        <div class="box-body no-padding">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center" width="3%">
                            <input type="checkbox" id="chkAll">
                        </th>
                        <th class="text-center" width="4%">{{ __('message.index') }}</th>
                        <th>{{ __('promotions.image') }}</th>

                        <th>@sortablelink('name', __('promotions.name'))</th>
                        <th>{{ __('promotions.code') }}</th>
                        <th>{{ __('promotions.type') }}</th>
                        <th>{{ __('promotions.discount_type') }}</th>
                        <th class="text-right">{{ __('promotions.discount_value') }}</th>
                        <th class="text-right">{{ __('promotions.max_discount') }}</th>
                        <th class="text-right">{{ __('promotions.discount_min_order_value') }}</th>
                        <th class="text-right">{{ __('promotions.discount_max_uses') }}</th>
                        <th class="text-right">{{ __('promotions.discount_uses_count') }}</th>
                        <th class="text-center">{{ __('promotions.start_date') }}</th>
                        <th class="text-center">{{ __('promotions.end_date') }}</th>
                        <th class="text-center">{{ __('message.status') }}</th>

                        <th width="7%"></th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($promotions as $item)
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" name="chkId" value="{{ $item->id }}">
                            </td>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center" style="width: 100px;">
                                <img src="{{ Storage::url($item->image) }}" alt="{{ $item->title }}"
                                    style="max-width: 100%; height: auto;">
                            </td>
                            <td>
                                @can('PromotionController@show')
                                    <a href="{{ route('promotions.show', $item->id) }}" style="color:blue">
                                        {{ $item->title }}
                                    </a>
                                @else
                                    {{ $item->title }}
                                @endcan
                            </td>

                            <td style="cursor: pointer;" onclick="copyDiscountCode('{{ $item->discount_code }}')"> <i
                                    class="fa fa-copy"></i>
                                {{ $item->discount_code }}</td>
                            <td>{{ ucfirst($item->type) }}</td>
                            <td>
                                @if ($item->discount_type === 'fixed')
                                    Giảm tiền cố định
                                @elseif ($item->discount_type === 'percent')
                                    Giảm theo phần trăm
                                @else
                                    —
                                @endif
                            </td>

                            <td class="text-right">
                                {{ number_format($item->discount_value) }}
                            </td>
                            <td class="text-right">
                                {{ $item->discount_max_value ? number_format($item->discount_max_value) : '-' }}
                            </td>
                            <td class="text-right">
                                {{ $item->discount_min_order_value ? number_format($item->discount_min_order_value) : '-' }}
                            </td>
                            <td class="text-right">
                                {{ $item->discount_max_uses }}
                            </td>
                            <td class="text-right">
                                {{ $item->discount_uses_count }}
                            </td>

                            <td class="text-center">{{ $item->start_date->format(config('settings.format.date')) }}</td>
                            <td class="text-center">
                                {{ $item->end_date ? $item->end_date->format(config('settings.format.date')) : '-' }}</td>

                            <td class="text-center">
                                @switch($item->status)
                                    @case('active')
                                        <span class="label label-success">
                                            <i class="fa fa-check-circle"></i> Đang áp dụng
                                        </span>
                                    @break

                                    @case('upcoming')
                                        <span class="label label-info">
                                            <i class="fa fa-clock-o"></i> Sắp diễn ra
                                        </span>
                                    @break

                                    @case('expired')
                                        <span class="label label-default">
                                            <i class="fa fa-times-circle"></i> Hết hạn
                                        </span>
                                    @break

                                    @case('disabled')
                                        <span class="label label-danger">
                                            <i class="fa fa-ban"></i> Tắt
                                        </span>
                                    @break
                                @endswitch
                            </td>




                            {{-- Action --}}
                            <td class="dropdown">
                                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                    <i class="fal fa-tools"></i>
                                </button>
                                <div class="dropdown-menu p-0">
                                    @can('PromotionController@show')
                                        <a href="{{ route('promotions.show', $item->id) }}"
                                            class="btn btn-info btn-sm dropdown-item">
                                            <i class="fas fa-eye"></i> {{ __('message.view') }}
                                        </a>
                                    @endcan
                                    @can('PromotionController@update')
                                        <a href="{{ route('promotions.edit', $item->id) }}"
                                            class="btn btn-primary btn-sm dropdown-item">
                                            <i class="far fa-edit"></i> {{ __('message.edit') }}
                                        </a>
                                    @endcan
                                    @can('PromotionController@destroy')
                                        {!! Form::open([
                                            'method' => 'DELETE',
                                            'route' => ['promotions.destroy', $item->id],
                                            'style' => 'display:inline',
                                        ]) !!}
                                        {!! Form::button('<i class="far fa-trash-alt"></i> ' . __('message.delete'), [
                                            'type' => 'submit',
                                            'class' => 'btn btn-danger btn-sm dropdown-item show_confirm',
                                        ]) !!}
                                        {!! Form::close() !!}
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="text-center">
                                    {{ __('promotions.no_item') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>


            <div class="box-footer clearfix">
                <div id="btn-act">
                    @can('PromotionController@destroy')
                        <a href="#" id="deletePromotions" data-action="deletePromotions" class="btn-act btn btn-danger btn-sm"
                            title="{{ __('message.delete') }}">
                            <i class="fas fa-trash-alt" aria-hidden="true"></i>
                        </a>
                    @endcan
                    &nbsp;
                    @can('PromotionController@active')
                        <a href="#" id="activePromotions" data-action="activePromotions"
                            class="btn-act btn btn-success btn-sm" title="{{ __('message.approved') }}">
                            <i class="fa fa-check" aria-hidden="true"></i>
                        </a>
                    @endcan
                </div>
                <div class="page-footer pull-right">
                    {!! $promotions->appends(\Request::except('page'))->render() !!}
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
                    case 'activePromotions':
                        actTxt = 'duyệt';
                        classAlert = 'alert-success';
                        break;
                    case 'deletePromotions':
                        actTxt = 'xóa';
                        classAlert = 'alert-danger';
                        break;
                }
                if (chkId.length != 0) {
                    swal({
                            title: 'Bạn có muốn ' + actTxt +
                                ' loại dịch vụ này không?',
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
                    swal("Lỗi!", 'Vui lòng chọn tin tức để  ' + actTxt + '!', "error")
                }
            }
        </script>
        <script>
            function copyDiscountCode(code) {
                navigator.clipboard.writeText(code).then(() => {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Đã copy mã khuyến mãi',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true
                    });
                }).catch(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Không thể copy mã khuyến mãi'
                    });
                });
            }
        </script>
    @endsection
