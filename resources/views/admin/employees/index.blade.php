@extends('adminlte::layouts.app')
@section('style')
    <style>
        .select2 {
            width: 250px;
        }
    </style>
@endsection
@section('htmlheader_title')
    {{ __('nhân viên') }}
@endsection
@section('contentheader_title')
    {{ __('nhân viên') }}
@endsection
@section('contentheader_description')
@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url('admin') }}"><i class="fa fa-home"></i> {{ __('message.dashboard') }}</a></li>
        <li class="active">{{ __('nhân viên') }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="content-header border-bottom pb-5">
            <h5 class="float-left">
                {{ __('message.lists') }}
            </h5>
            @can('EmployeeController@store')
                <a href="{{ url('/admin/employees/create') }}" class="btn btn-default float-right">
                    <i class="fa fa-plus-circle"></i> {{ __('message.new_add') }}
                </a>
            @endcan

        </div>

        <div class="box-header">
            <div class="box-tools">
                {!! Form::open(['method' => 'GET', 'route' => 'employees.index', 'class' => 'pull-left']) !!}
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
                        placeholder="{{ __('employees.search_placeholder') }}" style="width:260px">

                    <button class="btn btn-secondary btn-sm" type="submit">
                        <i class="fa fa-search"></i> {{ __('message.search') }}
                    </button>

                </div>
                {!! Form::close() !!}
            </div>
        </div>



        @php($index = ($employees->currentPage() - 1) * $employees->perPage())

        <div class="box-body no-padding">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center" width="3%">
                            <input type="checkbox" id="chkAll">
                        </th>
                        <th class="text-center" width="4%">{{ __('message.index') }}</th>

                        <th>{{ __('employees.code') }}</th>
                        <th>{{ __('employees.name') }}</th>
                        <th>{{ __('employees.avatar') }}</th>
                        <th>{{ __('employees.phone') }}</th>
                        <th>{{ __('employees.branch') }}</th>
                        <th class="text-center">{{ __('message.active') }}</th>
                        <th>{{ __('message.created_at') }}</th>
                        <th>{{ __('message.updated_at') }}</th>
                        <th width="7%"></th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($employees as $index => $item)
                        <tr>
                            {{-- CHECKBOX --}}
                            <td class="text-center">
                                <input type="checkbox" name="chkId" value="{{ $item->id }}">
                            </td>

                            {{-- INDEX --}}
                            <td class="text-center">{{ $index + 1 }}</td>

                            {{-- CODE --}}
                            <td>{{ $item->code ?? '—' }}</td>

                            {{-- NAME --}}
                            <td>
                                @can('EmployeeController@show')
                                    <a href="{{ route('employees.show', $item->id) }}" style="color:blue">
                                        {{ $item->name }}
                                    </a>
                                @else
                                    {{ $item->name }}
                                @endcan
                            </td>

                            {{-- AVATAR --}}
                            <td class="text-center">
                                @if (!empty($item->avatar))
                                    <img src="{{ Storage::url($item->avatar) }}" alt="{{ $item->name }}"
                                        style="width:50px;height:50px;object-fit:cover;border-radius:50%;border:1px solid #ddd;">
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            {{-- PHONE --}}
                            <td>{{ $item->phone ?? '—' }}</td>

                            {{-- BRANCH --}}
                            <td>
                                {{ $item->branch->name ?? '—' }}
                            </td>

                            {{-- ACTIVE --}}
                            <td class="text-center">
                                {!! $item->is_active ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>' !!}
                            </td>

                            {{-- CREATED --}}
                            <td>
                                {{ $item->created_at->format(config('settings.format.date')) }}
                            </td>

                            {{-- UPDATED --}}
                            <td>
                                {{ $item->updated_at->format(config('settings.format.date')) }}
                            </td>

                            {{-- ACTION --}}
                            <td class="dropdown">
                                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                    <i class="fal fa-tools"></i>
                                </button>

                                <div class="dropdown-menu p-0">
                                    @can('EmployeeController@show')
                                        <a href="{{ route('employees.show', $item->id) }}"
                                            class="btn btn-info btn-sm dropdown-item">
                                            <i class="fas fa-eye"></i> {{ __('message.view') }}
                                        </a>
                                    @endcan

                                    @can('EmployeeController@update')
                                        <a href="{{ route('employees.edit', $item->id) }}"
                                            class="btn btn-primary btn-sm dropdown-item">
                                            <i class="far fa-edit"></i> {{ __('message.edit') }}
                                        </a>
                                    @endcan

                                    @can('EmployeeController@destroy')
                                        {!! Form::open([
                                            'method' => 'DELETE',
                                            'route' => ['employees.destroy', $item->id],
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
                            <td colspan="11" class="text-center">
                                {{ __('employees.no_item') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>



        <div class="box-footer clearfix">
            <div id="btn-act">
                @can('EmployeeController@destroy')
                    <a href="#" id="deleteEmployees" data-action="deleteEmployees" class="btn-act btn btn-danger btn-sm"
                        title="{{ __('message.delete') }}">
                        <i class="fas fa-trash-alt" aria-hidden="true"></i>
                    </a>
                @endcan
                &nbsp;
                @can('EmployeeController@active')
                    <a href="#" id="activeEmployees" data-action="activeEmployees" class="btn-act btn btn-success btn-sm"
                        title="{{ __('message.approved') }}">
                        <i class="fa fa-check" aria-hidden="true"></i>
                    </a>
                @endcan
            </div>
            <div class="page-footer pull-right">
                {!! $employees->appends(\Request::except('page'))->render() !!}
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
                case 'activeEmployees':
                    actTxt = 'duyệt';
                    classAlert = 'alert-success';
                    break;
                case 'deleteEmployees':
                    actTxt = 'xóa';
                    classAlert = 'alert-danger';
                    break;
            }
            if (chkId.length != 0) {
                swal({
                        title: 'Bạn có muốn ' + actTxt +
                            ' nhân viên này không?',
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
                swal("Lỗi!", 'Vui lòng chọn nhân viên để ' + actTxt + '!', "error")
            }
        }
    </script>
@endsection
