@extends('adminlte::layouts.app')
@section('style')
    <style>
        .select2 {
            width: 250px;
        }
    </style>
@endsection
@section('htmlheader_title')
    {{ __('branch_room_types.branch_room_types') }}
@endsection
@section('contentheader_title')
    {{ __('branch_room_types.branch_room_types') }}
@endsection
@section('contentheader_description')
@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url('admin') }}"><i class="fa fa-home"></i> {{ __('message.dashboard') }}</a></li>
        <li class="active">{{ __('branch_room_types.branch_room_types') }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="content-header border-bottom pb-5">
            <h5 class="float-left">
                {{ __('message.lists') }}
            </h5>
            @can('BranchRoomTypeController@store')
                <a href="{{ url('/admin/branch-room-types/create') }}" class="btn btn-default float-right"
                    title="{{ __('message.new_add') }}">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i> <span class="hidden-xs">
                        {{ __('message.new_add') }}</span>
                </a>
            @endcan
        </div>

        <div class="box-header">
            <div class="box-tools">
                {!! Form::open([
                    'method' => 'GET',
                    'url' => route('branch-room-types.index'),
                    'class' => 'pull-left',
                    'role' => 'search',
                ]) !!}

                <div class="filter-bar d-flex align-items-center gap-2">

                    {{-- Chi nhánh --}}
                    <div class="filter-item">
                        {!! Form::select('branch_id', ['' => __('branch_room_types.branch')] + $branches, request('branch_id'), [
                            'class' => 'form-control input-sm select2',
                        ]) !!}
                    </div>

                    {{-- Loại phòng --}}
                    <div class="filter-item">
                        {!! Form::select(
                            'room_type_id',
                            ['' => __('branch_room_types.room_type')] + $roomTypes,
                            request('room_type_id'),
                            ['class' => 'form-control input-sm select2'],
                        ) !!}
                    </div>

                    {{-- Trạng thái --}}
                    <div class="filter-item">
                        {!! Form::select(
                            'is_active',
                            [
                                '' => __('message.status'),
                                '1' => __('branch_room_types.active'),
                                '0' => __('branch_room_types.inactive'),
                            ],
                            request('is_active'),
                            ['class' => 'form-control input-sm select2'],
                        ) !!}
                    </div>

                    {{-- Keyword --}}
                    <div class="filter-item">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control input-sm" style="width: 170px;"
                            placeholder="{{ __('message.search_keyword') }}">
                    </div>

                    {{-- Button --}}
                    <div class="filter-item">
                        <button class="btn btn-secondary btn-sm" type="submit">
                            <i class="fa fa-search"></i> {{ __('message.search') }}
                        </button>
                    </div>

                </div>

                {!! Form::close() !!}
            </div>
        </div>




        @php($index = ($branchRoomTypes->currentPage() - 1) * $branchRoomTypes->perPage())
        <div class="box-body no-padding">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center" style="width:3%">
                            <input type="checkbox" id="chkAll">
                        </th>
                        <th class="text-center" style="width:3%">
                            {{ trans('message.index') }}
                        </th>

                        <th>{{ __('room_types.code') }}</th>
                        <th>{{ __('room_types.name') }}</th>
                        <th class="text-center">{{ __('room_types.capacity') }}</th>
                        <th class="text-center">{{ __('room_types.price') }}</th>
                        <th>{{ __('branches.branch') }}</th>
                        <th class="text-center">{{ __('room_types.is_active') }}</th>
                        <th>{{ __('room_types.updated_at') }}</th>
                        <th style="width:7%"></th>
                    </tr>
                </thead>

                <tbody>
                    @php($index = ($branchRoomTypes->currentPage() - 1) * $branchRoomTypes->perPage())

                    @forelse ($branchRoomTypes as $item)
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" name="chkId" value="{{ $item->id }}">
                            </td>

                            <td class="text-center">{{ ++$index }}</td>

                            <td>{{ $item->roomType->code ?? '-' }}</td>

                            <td>{{ $item->roomType->name ?? '-' }}</td>

                            <td class="text-center">{{ $item->capacity }}</td>

                            <td class="text-center">
                                {{ number_format($item->price, 0, ',', '.') }}
                            </td>

                            <td>
                                {{ $item->branch->name ?? '-' }}
                            </td>

                            <td class="text-center">
                                {!! $item->is_active ? '<i class="fa fa-check text-primary"></i>' : '<i class="fa fa-times text-danger"></i>' !!}
                            </td>

                            <td>
                                {{ $item->updated_at->format(config('settings.format.date')) }}
                            </td>

                            <td class="dropdown">
                                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                    <i class="fal fa-tools"></i>
                                </button>

                                <div class="dropdown-menu p-0">
                                    @can('BranchRoomTypeController@show')
                                        <a href="{{ route('branch-room-types.show', $item->id) }}"
                                            class="btn btn-info btn-sm dropdown-item">
                                            <i class="fas fa-eye"></i> {{ __('message.view') }}
                                        </a>
                                    @endcan

                                    @can('BranchRoomTypeController@update')
                                        <a href="{{ route('branch-room-types.edit', $item->id) }}"
                                            class="btn btn-primary btn-sm dropdown-item">
                                            <i class="far fa-edit"></i> {{ __('message.edit') }}
                                        </a>
                                    @endcan

                                    @can('BranchRoomTypeController@destroy')
                                        {!! Form::open([
                                            'method' => 'DELETE',
                                            'route' => ['branch-room-types.destroy', $item->id],
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
                            <td colspan="10" class="text-center">
                                {{ __('room_types.no_item') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>


        <div class="box-footer clearfix">
            <div id="btn-act">
                @can('BranchRoomTypeController@destroy')
                    <a href="#" id="deleteBranchRoomTypes" data-action="deleteBranchRoomTypes" class="btn-act btn btn-danger btn-sm"
                        title="{{ __('message.delete') }}">
                        <i class="fas fa-trash-alt" aria-hidden="true"></i>
                    </a>
                @endcan
                &nbsp;
                @can('BranchRoomTypeController@active')
                    <a href="#" id="activeBranchRoomTypes" data-action="activeBranchRoomTypes" class="btn-act btn btn-success btn-sm"
                        title="{{ __('message.approved') }}">
                        <i class="fa fa-check" aria-hidden="true"></i>
                    </a>
                @endcan
            </div>
            <div class="page-footer pull-right">
                {!! $branchRoomTypes->appends(\Request::except('page'))->render() !!}
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
                case 'activeBranchRoomTypes':
                    actTxt = 'duyệt';
                    classAlert = 'alert-success';
                    break;
                case 'deleteBranchRoomTypes':
                    actTxt = 'xóa';
                    classAlert = 'alert-danger';
                    break;
            }
            if (chkId.length != 0) {
                swal({
                        title: 'Bạn có muốn ' + actTxt +
                            ' bản ghi này không?',
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
                swal("Lỗi!", 'Vui lòng chọn bản ghi để  ' + actTxt + '!', "error")
            }
        }
    </script>
@endsection
