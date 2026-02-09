@extends('adminlte::layouts.app')

@section('htmlheader_title')
    {{ __('employee_services.employee_services') }}
@endsection

@section('contentheader_title')
    {{ __('employee_services.employee_services') }}
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
            <a href="{{ url('/admin/employee-services') }}">
                {{ __('employee_services.employee_services') }}
            </a>
        </li>
        <li class="active">{{ __('message.edit_title') }}</li>
    </ol>
@endsection

@section('main-content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">{{ __('message.edit_title') }}</h3>
            <div class="box-tools">
                <a href="{{ !empty($backUrl) ? $backUrl : url('/admin/employee-services') }}" class="btn btn-warning btn-sm">
                    <i class="fa fa-arrow-left"></i>
                    <span class="hidden-xs">{{ __('message.lists') }}</span>
                </a>
            </div>
        </div>

        {!! Form::model($employee, [
            'method' => 'PATCH',
            'url' => ['/admin/employee-services', $employee->id],
            'class' => 'form-horizontal',
        ]) !!}


        @include('admin.employee_services.form', [
            'submitButtonText' => __('message.update'),
        ])

        {!! Form::close() !!}
    </div>
@endsection
