@extends('adminlte::layouts.app')

@section('htmlheader_title')
    {{ __('employee_working_shifts.detail') }}
@endsection

@section('contentheader_title')
    {{ __('employee_working_shifts.detail') }}
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li>
            <a href="{{ url('admin') }}">
                <i class="fa fa-home"></i> {{ __('message.dashboard') }}
            </a>
        </li>
        <li>
            <a href="{{ route('employee-working-shifts.index') }}">
                {{ __('employee_working_shifts.employee_working_shifts') }}
            </a>
        </li>
        <li class="active">{{ __('message.detail') }}</li>
    </ol>
@endsection

@section('main-content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{ __('message.detail') }}</h3>

            <div class="box-tools pull-right">
                <a href="{{ route('employee-working-shifts.index') }}" class="btn btn-default btn-sm">
                    <i class="fa fa-arrow-left"></i>
                    {{ __('message.lists') }}
                </a>
            </div>
        </div>

        <div class="box-body table-responsive no-padding">
            <table class="table table-bordered">
                <tbody>

                    {{-- EMPLOYEE --}}
                    <tr>
                        <th style="width:25%">{{ __('employees.employee') }}</th>
                        <td>
                            <strong>{{ $employeeWorkingShift->employee->name }}</strong>
                            <br>
                            <small class="text-muted">
                                {{ $employeeWorkingShift->employee->code }}
                            </small>
                        </td>
                    </tr>

                    {{-- BRANCH --}}
                    <tr>
                        <th>{{ __('employees.branch') }}</th>
                        <td>
                            {{ $employeeWorkingShift->employee->branch->name ?? '—' }}
                        </td>
                    </tr>

                    {{-- WORK DATE --}}
                    <tr>
                        <th>{{ __('working_shifts.work_date') }}</th>
                        <td>
                            <span class="label label-info">
                                {{ $employeeWorkingShift->work_date->format(config('settings.format.date')) }}
                            </span>
                        </td>
                    </tr>

                    {{-- WORKING SHIFT --}}
                    <tr>
                        <th>{{ __('working_shifts.working_shift') }}</th>
                        <td>
                            <strong>{{ $employeeWorkingShift->workingShift->name }}</strong>
                            <br>
                            <small class="text-muted">
                                {{ $employeeWorkingShift->workingShift->start_time }}
                                –
                                {{ $employeeWorkingShift->workingShift->end_time }}
                            </small>
                        </td>
                    </tr>

                    {{-- CREATED --}}
                    <tr>
                        <th>{{ __('message.created_at') }}</th>
                        <td>
                            {{ optional($employeeWorkingShift->created_at)->format(config('settings.format.datetime')) }}
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
@endsection
