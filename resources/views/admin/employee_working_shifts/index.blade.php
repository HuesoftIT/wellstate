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
        <div class="content-header border-bottom pb-5 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                {{ __('message.lists') }}
            </h5>

            <div class="btn-group">
                @can('EmployeeWorkingShiftController@index')
                    <a href="{{ route('employee-working-shifts.calendar') }}" class="btn btn-outline-primary">
                        <i class="fa fa-calendar"></i> Lịch phân ca
                    </a>
                @endcan

                @can('EmployeeWorkingShiftController@store')
                    <a href="{{ route('employee-working-shifts.create') }}" class="btn btn-default">
                        <i class="fa fa-plus-circle"></i> {{ __('message.new_add') }}
                    </a>
                @endcan
            </div>
        </div>


        <div class="box-header">
            <div class="box-tools">
                {!! Form::open([
                    'method' => 'GET',
                    'route' => 'employee-working-shifts.index',
                    'class' => 'pull-left',
                ]) !!}

                <div class="input-group" style="display:flex; gap:8px; align-items:center;">

                    {{-- BRANCH --}}
                    {!! Form::select('branch_id', $branches, request('branch_id'), [
                        'class' => 'form-control input-sm',
                        'placeholder' => __('employees.branch'),
                        'style' => 'width:180px',
                    ]) !!}

                    <input type="text" name="employee_name" value="{{ request('employee_name') }}"
                        class="form-control input-sm" placeholder="Tên nhân viên" style="width:180px">

                    {{-- WORKING SHIFT --}}
                    {!! Form::select('working_shift_id', $workingShifts, request('working_shift_id'), [
                        'class' => 'form-control input-sm',
                        'placeholder' => __('working_shifts.working_shift'),
                        'style' => 'width:180px',
                    ]) !!}

                    {{-- WORK DATE --}}
                    <input type="date" name="work_date" value="{{ request('work_date') }}" class="form-control input-sm"
                        style="width:160px">

                    <button class="btn btn-secondary btn-sm" type="submit">
                        <i class="fa fa-search"></i> {{ __('message.search') }}
                    </button>

                </div>

                {!! Form::close() !!}
            </div>
        </div>



        @php($index = ($employeeWorkingShifts->currentPage() - 1) * $employeeWorkingShifts->perPage())

        <div class="box-body no-padding">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center" width="3%">
                            <input type="checkbox" id="chkAll">
                        </th>
                        <th class="text-center" width="4%">{{ __('message.index') }}</th>

                        <th>{{ __('employees.employee') }}</th>
                        <th>{{ __('employees.branch') }}</th>
                        <th>{{ __('working_shifts.name') }}</th>
                        <th class="text-center">{{ __('employee_working_shifts.work_date') }}</th>
                        <th class="text-center">{{ __('working_shifts.start_time') }}</th>
                        <th class="text-center">{{ __('working_shifts.end_time') }}</th>
                        <th>{{ __('message.created_at') }}</th>
                        <th width="7%"></th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($employeeWorkingShifts as $key => $item)
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" name="chkId" value="{{ $item->id }}">
                            </td>

                            <td class="text-center">{{ $index + $key + 1 }}</td>

                            <td>
                                @can('EmployeeWorkingShiftController@show')
                                    <a href="{{ route('employee-working-shifts.show', $item->id) }}" style="color:blue">
                                        {{ $item->employee->name ?? '—' }}
                                    </a>
                                @else
                                    {{ $item->employee->name ?? '—' }}
                                @endcan
                            </td>

                            <td>
                                {{ $item->employee->branch->name ?? '—' }}
                            </td>

                            <td>
                                {{ $item->workingShift->name ?? '—' }}
                            </td>

                            <td class="text-center">
                                {{ optional($item->work_date)->format(config('settings.format.date')) ?? '—' }}
                            </td>

                            <td class="text-center">
                                {{ optional($item->workingShift)->start_time
                                    ? \Carbon\Carbon::parse($item->workingShift->start_time)->format('H:i')
                                    : '—' }}
                            </td>

                            <td class="text-center">
                                {{ optional($item->workingShift)->end_time
                                    ? \Carbon\Carbon::parse($item->workingShift->end_time)->format('H:i')
                                    : '—' }}
                            </td>

                            <td>
                                {{ optional($item->created_at)->format(config('settings.format.date')) ?? '—' }}
                            </td>

                            <td class="dropdown">
                                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                    <i class="fal fa-tools"></i>
                                </button>

                                <div class="dropdown-menu p-0">
                                    @can('EmployeeWorkingShiftController@show')
                                        <a href="{{ route('employee-working-shifts.show', $item->id) }}"
                                            class="btn btn-info btn-sm dropdown-item">
                                            <i class="fas fa-eye"></i> {{ __('message.view') }}
                                        </a>
                                    @endcan

                                    @can('EmployeeWorkingShiftController@update')
                                        <a href="{{ route('employee-working-shifts.edit', $item->id) }}"
                                            class="btn btn-primary btn-sm dropdown-item">
                                            <i class="far fa-edit"></i> {{ __('message.edit') }}
                                        </a>
                                    @endcan

                                    @can('EmployeeWorkingShiftController@destroy')
                                        {!! Form::open([
                                            'method' => 'DELETE',
                                            'route' => ['employee-working-shifts.destroy', $item->id],
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
                                {{ __('employee_working_shifts.no_item') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>


        <div class="box-footer clearfix">
            <div id="btn-act">
                @can('EmployeeWorkingShiftController@destroy')
                    <a href="#" id="deleteEmployeeWorkingShifts" data-action="deleteEmployeeWorkingShifts"
                        class="btn-act btn btn-danger btn-sm" title="{{ __('message.delete') }}">
                        <i class="fas fa-trash-alt" aria-hidden="true"></i>
                    </a>
                @endcan
                &nbsp;
                @can('EmployeeWorkingShiftController@active')
                    <a href="#" id="activeEmployeeWorkingShifts" data-action="activeEmployeeWorkingShifts"
                        class="btn-act btn btn-success btn-sm" title="{{ __('message.approved') }}">
                        <i class="fa fa-check" aria-hidden="true"></i>
                    </a>
                @endcan
            </div>
            <div class="page-footer pull-right">
                {!! $employeeWorkingShifts->appends(\Request::except('page'))->render() !!}
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
                case 'activeEmployeeWorkingShifts':
                    actTxt = 'duyệt';
                    classAlert = 'alert-success';
                    break;
                case 'deleteEmployeeWorkingShifts':
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
