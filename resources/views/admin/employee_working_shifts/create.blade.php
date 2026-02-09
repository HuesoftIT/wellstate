@extends('adminlte::layouts.app')

@section('htmlheader_title')
    {{ __('working_shifts.working_shifts') }}
@endsection

@section('contentheader_title')
    {{ __('working_shifts.working_shifts') }}
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
            <a href="{{ url('/admin/employee-working-shifts') }}">
                {{ __('working_shifts.working_shifts') }}
            </a>
        </li>
        <li class="active">{{ __('message.new_add') }}</li>
    </ol>
@endsection

@section('main-content')
    <div class="box box-success">

        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="fa fa-clock-o"></i>
                {{ __('message.new_add') }}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ url('/admin/employee-working-shifts') }}" class="btn btn-default btn-sm">
                    <i class="fa fa-arrow-left"></i>
                    <span class="hidden-xs">{{ __('message.lists') }}</span>
                </a>
            </div>
        </div>

        {!! Form::open([
            'url' => '/admin/employee-working-shifts',
            'class' => 'form-horizontal',
        ]) !!}

        @include('admin.employee_working_shifts.form')

        {!! Form::close() !!}
    </div>
@endsection
