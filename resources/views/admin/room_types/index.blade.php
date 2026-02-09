@extends('adminlte::layouts.app')
@section('style')
    <style>
        .select2 {
            width: 250px;
        }
    </style>
@endsection
@section('htmlheader_title')
    {{ __('room_types.room_types') }}
@endsection
@section('contentheader_title')
    {{ __('room_types.room_types') }}
@endsection
@section('contentheader_description')
@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url('admin') }}"><i class="fa fa-home"></i> {{ __('message.dashboard') }}</a></li>
        <li class="active">{{ __('room_types.room_types') }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="content-header border-bottom pb-5">
            <h5 class="float-left">
                {{ __('message.lists') }}
            </h5>
            @can('RoomTypeController@store')
                <a href="{{ url('/admin/room-types/create') }}" class="btn btn-default float-right"
                    title="{{ __('message.new_add') }}">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i> <span class="hidden-xs">
                        {{ __('message.new_add') }}</span>
                </a>
            @endcan
        </div>

        <div class="box-header with-border">
            <div class="box-tools pull-left">
                {!! Form::open([
                    'method' => 'GET',
                    'url' => route('room-types.index'),
                    'role' => 'search',
                    'class' => 'form-inline',
                ]) !!}

                <div class="form-group mr-2">
                    {!! Form::select(
                        'is_active',
                        [
                            '' => __('message.status'),
                            '1' => __('room_types.active'),
                            '0' => __('room_types.inactive'),
                        ],
                        request('is_active'),
                        ['class' => 'form-control input-sm select2', 'style' => 'min-width:150px'],
                    ) !!}
                </div>

                <div class="form-group mr-2">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control input-sm"
                        placeholder="{{ __('message.search_keyword') }}" style="min-width:250px">
                </div>

                <div class="form-group">
                    <button class="btn btn-secondary btn-sm" type="submit">
                        <i class="fa fa-search"></i> {{ __('message.search') }}
                    </button>
                </div>

                {!! Form::close() !!}
            </div>
        </div>


        @php($index = ($roomTypes->currentPage() - 1) * $roomTypes->perPage())
        <div class="box-body no-padding">
            <table class="table table-bordered table-hover">
                <tbody>
                    <tr>
                        <th class="text-center" style="width:3.5%">
                            <input type="checkbox" name="chkAll" id="chkAll">
                        </th>
                        <th class="text-center" style="width:3.5%">
                            {{ trans('message.index') }}
                        </th>

                        <th>@sortablelink('code', __('room_types.code'))</th>
                        <th>@sortablelink('name', __('room_types.name'))</th>

                        <th class="text-center" width="8%">{{ __('room_types.is_active') }}</th>
                        <th>@sortablelink('updated_at', __('room_types.updated_at'))</th>
                        <th style="width:7%"></th>
                    </tr>

                    @foreach ($roomTypes as $item)
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" name="chkId" value="{{ $item->id }}">
                            </td>

                            <td class="text-center">{{ ++$index }}</td>

                            @can('RoomTypeController@show')
                                <td>
                                    <a href="{{ route('room-types.show', $item->id) }}" style="color:black">
                                        {{ $item->code }}
                                    </a>
                                </td>
                            @endcan

                            <td>{{ $item->name }}</td>

                            <td class="text-center">
                                {!! $item->is_active ? '<i class="fa fa-check text-primary"></i>' : '<i class="fa fa-times text-danger"></i>' !!}
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($item->updated_at)->format(config('settings.format.date')) }}
                            </td>

                            <td class="dropdown">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                    <i class="fal fa-tools"></i>
                                </button>

                                <div class="dropdown-menu p-0">
                                    @can('RoomTypeController@show')
                                        <a href="{{ route('room-types.show', $item->id) }}"
                                            class="btn btn-info btn-sm dropdown-item">
                                            <i class="fas fa-eye"></i> {{ __('message.view') }}
                                        </a>
                                    @endcan

                                    @can('RoomTypeController@update')
                                        <a href="{{ route('room-types.edit', $item->id) }}"
                                            class="btn btn-primary btn-sm dropdown-item">
                                            <i class="far fa-edit"></i> {{ __('message.edit') }}
                                        </a>
                                    @endcan

                                    @can('RoomTypeController@destroy')
                                        {!! Form::open([
                                            'method' => 'DELETE',
                                            'route' => ['room-types.destroy', $item->id],
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
                    @endforeach
                </tbody>

                @if ($roomTypes->count() == 0)
                    <tr>
                        <td class="text-center" colspan="9">
                            {{ __('room_types.no_item') }}
                        </td>
                    </tr>
                @endif
            </table>
        </div>

        <div class="box-footer clearfix">
            <div id="btn-act">
                @can('RoomTypeController@destroy')
                    <a href="#" id="deleteRoomTypes" data-action="deleteRoomTypes" class="btn-act btn btn-danger btn-sm"
                        title="{{ __('message.delete') }}">
                        <i class="fas fa-trash-alt" aria-hidden="true"></i>
                    </a>
                @endcan
                &nbsp;
                @can('RoomTypeController@active')
                    <a href="#" id="activeRoomTypes" data-action="activeRoomTypes" class="btn-act btn btn-success btn-sm"
                        title="{{ __('message.approved') }}">
                        <i class="fa fa-check" aria-hidden="true"></i>
                    </a>
                @endcan
            </div>
            <div class="page-footer pull-right">
                {!! $roomTypes->appends(\Request::except('page'))->render() !!}
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
                case 'activeRoomTypes':
                    actTxt = 'duyệt';
                    classAlert = 'alert-success';
                    break;
                case 'deleteRoomTypes':
                    actTxt = 'xóa';
                    classAlert = 'alert-danger';
                    break;
            }
            if (chkId.length != 0) {
                swal({
                        title: 'Bạn có muốn ' + actTxt +
                            ' hạng phòng này không?',
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
                swal("Lỗi!", 'Vui lòng chọn hạng phòng để  ' + actTxt + '!', "error")
            }
        }
    </script>
@endsection
