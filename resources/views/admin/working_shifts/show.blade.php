@extends('adminlte::layouts.app')

@section('htmlheader_title')
    {{ __('working_shifts.working_shift') }}
@endsection

@section('contentheader_title')
    {{ __('working_shifts.working_shift') }}
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li>
            <a href="{{ url('admin') }}">
                <i class="fa fa-home"></i> {{ __('message.dashboard') }}
            </a>
        </li>
        <li>
            <a href="{{ route('working-shifts.index') }}">
                {{ __('working_shifts.working_shifts') }}
            </a>
        </li>
        <li class="active">{{ __('message.detail') }}</li>
    </ol>
@endsection

@section('main-content')
    {{-- ================= BASIC INFO ================= --}}
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{ __('message.detail') }}</h3>

            <div class="box-tools pull-right">
                <a href="{{ route('working-shifts.index') }}" class="btn btn-default btn-sm">
                    <i class="fa fa-arrow-left"></i>
                    <span class="hidden-xs">{{ __('message.lists') }}</span>
                </a>

                @can('WorkingShiftController@update')
                    <a href="{{ route('working-shifts.edit', $workingShift->id) }}" class="btn btn-primary btn-sm">
                        <i class="far fa-edit"></i>
                        <span class="hidden-xs">{{ __('message.edit') }}</span>
                    </a>
                @endcan
            </div>
        </div>

        <div class="box-body table-responsive no-padding">
            <table class="table table-bordered table-striped">
                <tbody>

                    <tr>
                        <th style="width:20%">{{ __('working_shifts.name') }}</th>
                        <td><strong>{{ $workingShift->name }}</strong></td>
                    </tr>

                    <tr>
                        <th>{{ __('working_shifts.branch') }}</th>
                        <td>{{ $workingShift->branch->name ?? '—' }}</td>
                    </tr>

                    <tr>
                        <th>{{ __('working_shifts.time') }}</th>
                        <td>
                            <span class="label label-primary">
                                {{ $workingShift->start_time }} – {{ $workingShift->end_time }}
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <th>{{ __('message.status') }}</th>
                        <td>
                            @if ($workingShift->is_active)
                                <span class="label label-success">{{ __('message.active') }}</span>
                            @else
                                <span class="label label-default">{{ __('message.inactive') }}</span>
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>{{ __('message.created_at') }}</th>
                        <td>{{ optional($workingShift->created_at)->format(config('settings.format.datetime')) }}</td>
                    </tr>

                    <tr>
                        <th>{{ __('message.updated_at') }}</th>
                        <td>{{ optional($workingShift->updated_at)->format(config('settings.format.datetime')) }}</td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>

    {{-- ================= EMPLOYEES IN SHIFT ================= --}}
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="fa fa-users"></i>
                {{ __('working_shifts.employees_in_shift') }}
            </h3>
        </div>

        <div class="box-body table-responsive no-padding">
            @if ($workingShift->employees->isEmpty())
                <div class="alert alert-warning" style="margin:15px;">
                    <i class="fa fa-info-circle"></i>
                    {{ __('working_shifts.no_employee_assigned') }}
                </div>
            @else
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width:5%">#</th>
                            <th>{{ __('employees.code') }}</th>
                            <th>{{ __('employees.name') }}</th>
                            <th>{{ __('employees.phone') }}</th>
                            <th style="width:20%">{{ __('working_shifts.work_date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($workingShift->employees as $index => $employee)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><code>{{ $employee->code }}</code></td>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->phone ?? '—' }}</td>
                                <td>
                                    {{ optional($employee->pivot->work_date)->format(config('settings.format.date')) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
