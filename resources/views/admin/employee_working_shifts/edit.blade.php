@extends('adminlte::layouts.app')

@section('htmlheader_title')
    {{ __('employee_working_shifts.employee_working_shifts') }}
@endsection

@section('contentheader_title')
    {{ __('employee_working_shifts.employee_working_shifts') }}
@endsection

@section('contentheader_description')
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
        <li class="active">{{ __('message.edit_title') }}</li>
    </ol>
@endsection

@section('main-content')
    <div class="box box-success">

        {{-- HEADER --}}
        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="fa fa-calendar-check-o"></i>
                {{ __('message.edit_title') }}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ !empty($backUrl) ? $backUrl : route('employee-working-shifts.index') }}"
                    class="btn btn-warning btn-sm">
                    <i class="fa fa-arrow-left"></i>
                    <span class="hidden-xs">{{ __('message.lists') }}</span>
                </a>
            </div>
        </div>

        {{-- FORM --}}
        {!! Form::model($employeeWorkingShift, [
            'method' => 'PATCH',
            'route' => ['employee-working-shifts.update', $employeeWorkingShift->id],
            'class' => 'form-horizontal',
        ]) !!}

        @include('admin.employee_working_shifts.form', [
            'submitButtonText' => __('message.update'),
        ])

        {!! Form::close() !!}
    </div>
@endsection
