@extends('adminlte::layouts.app')
@section('style')
    <style>
        .select2 {
            width: 250px;
        }
    </style>
@endsection
@section('htmlheader_title')
    {{ __('ca làm việc') }}
@endsection
@section('contentheader_title')
    {{ __('ca làm việc') }}
@endsection
@section('contentheader_description')
@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url('admin') }}"><i class="fa fa-home"></i> {{ __('message.dashboard') }}</a></li>
        <li class="active">{{ __('ca làm việc') }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="content-header border-bottom pb-5">
            <h5 class="float-left">
                {{ __('message.lists') }}
            </h5>
            @can('WorkingShiftController@store')
                <a href="{{ url('/admin/working-shifts/create') }}" class="btn btn-default float-right">
                    <i class="fa fa-plus-circle"></i> {{ __('message.new_add') }}
                </a>
            @endcan

        </div>

        <div class="box-header">
            <div class="box-tools">
                {!! Form::open([
                    'method' => 'GET',
                    'route' => 'working-shifts.index',
                    'class' => 'pull-left',
                ]) !!}

                <div class="input-group" style="display:flex; gap:8px; align-items:center;">

                    {!! Form::select('branch_id', $branches, request('branch_id'), [
                        'class' => 'form-control input-sm',
                        'placeholder' => __('employees.branch'),
                        'style' => 'width:180px',
                    ]) !!}

                    {!! Form::select(
                        'is_active',
                        [
                            '1' => __('message.active'),
                            '0' => __('message.inactive'),
                        ],
                        request('is_active'),
                        [
                            'class' => 'form-control input-sm',
                            'placeholder' => __('message.status'),
                            'style' => 'width:150px',
                        ],
                    ) !!}

                    <input type="text" name="search" value="{{ request('search') }}" class="form-control input-sm"
                        placeholder="{{ __('working_shifts.search_placeholder') }}" style="width:260px">

                    <button class="btn btn-secondary btn-sm" type="submit">
                        <i class="fa fa-search"></i> {{ __('message.search') }}
                    </button>

                </div>

                {!! Form::close() !!}
            </div>
        </div>


        @php($index = ($workingShifts->currentPage() - 1) * $workingShifts->perPage())

        <div class="box-body no-padding">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center" width="3%">
                            <input type="checkbox" id="chkAll">
                        </th>
                        <th class="text-center" width="4%">{{ __('message.index') }}</th>

                        <th>{{ __('working_shifts.name') }}</th>
                        <th class="text-center">{{ __('working_shifts.start_time') }}</th>
                        <th class="text-center">{{ __('working_shifts.end_time') }}</th>
                        <th>{{ __('working_shifts.branch') }}</th>
                        <th class="text-center">{{ __('message.active') }}</th>
                        <th>{{ __('message.created_at') }}</th>
                        <th>{{ __('message.updated_at') }}</th>
                        <th width="7%"></th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($workingShifts as $index => $item)
                        <tr>
                            {{-- CHECKBOX --}}
                            <td class="text-center">
                                <input type="checkbox" name="chkId" value="{{ $item->id }}">
                            </td>

                            {{-- INDEX --}}
                            <td class="text-center">{{ $index + 1 }}</td>

                            {{-- NAME --}}
                            <td>
                                @can('WorkingShiftController@show')
                                    <a href="{{ route('working-shifts.show', $item->id) }}" style="color:blue">
                                        {{ $item->name }}
                                    </a>
                                @else
                                    {{ $item->name }}
                                @endcan
                            </td>

                            {{-- START TIME --}}
                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($item->start_time)->format('H:i') }}
                            </td>

                            {{-- END TIME --}}
                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($item->end_time)->format('H:i') }}
                            </td>

                            {{-- BRANCH --}}
                            <td>
                                {{ $item->branch->name ?? '—' }}
                            </td>

                            {{-- ACTIVE --}}
                            <td class="text-center">
                                {!! $item->is_active ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>' !!}
                            </td>

                            <td>
                                {{ optional($item->created_at)->format(config('settings.format.date')) ?? '—' }}
                            </td>

                            <td>
                                {{ optional($item->updated_at)->format(config('settings.format.date')) ?? '—' }}
                            </td>


                            {{-- ACTION --}}
                            <td class="dropdown">
                                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                    <i class="fal fa-tools"></i>
                                </button>

                                <div class="dropdown-menu p-0">
                                    @can('WorkingShiftController@show')
                                        <a href="{{ route('working-shifts.show', $item->id) }}"
                                            class="btn btn-info btn-sm dropdown-item">
                                            <i class="fas fa-eye"></i> {{ __('message.view') }}
                                        </a>
                                    @endcan

                                    @can('WorkingShiftController@update')
                                        <a href="{{ route('working-shifts.edit', $item->id) }}"
                                            class="btn btn-primary btn-sm dropdown-item">
                                            <i class="far fa-edit"></i> {{ __('message.edit') }}
                                        </a>
                                    @endcan

                                    @can('WorkingShiftController@destroy')
                                        {!! Form::open([
                                            'method' => 'DELETE',
                                            'route' => ['working-shifts.destroy', $item->id],
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
                                {{ __('working_shifts.no_item') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>




        <div class="box-footer clearfix">
            <div id="btn-act">
                @can('WorkingShiftController@destroy')
                    <a href="#" id="deleteWorkingShifts" data-action="deleteWorkingShifts" class="btn-act btn btn-danger btn-sm"
                        title="{{ __('message.delete') }}">
                        <i class="fas fa-trash-alt" aria-hidden="true"></i>
                    </a>
                @endcan
                &nbsp;
                @can('WorkingShiftController@active')
                    <a href="#" id="activeWorkingShifts" data-action="activeWorkingShifts" class="btn-act btn btn-success btn-sm"
                        title="{{ __('message.approved') }}">
                        <i class="fa fa-check" aria-hidden="true"></i>
                    </a>
                @endcan
            </div>
            <div class="page-footer pull-right">
                {!! $workingShifts->appends(\Request::except('page'))->render() !!}
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
                case 'activeWorkingShifts':
                    actTxt = 'duyệt';
                    classAlert = 'alert-success';
                    break;
                case 'deleteWorkingShifts':
                    actTxt = 'xóa';
                    classAlert = 'alert-danger';
                    break;
            }
            if (chkId.length != 0) {
                swal({
                        title: 'Bạn có muốn ' + actTxt +
                            ' ca làm việc này không?',
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
                swal("Lỗi!", 'Vui lòng chọn ca làm việc để ' + actTxt + '!', "error")
            }
        }
    </script>
@endsection
