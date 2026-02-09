@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ __('branches.branches') }}
@endsection
@section('contentheader_title')
    {{ __('branches.branches') }}
@endsection
@section('contentheader_description')
@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url('admin') }}"><i class="fa fa-home"></i> {{ __('message.dashboard') }}</a></li>
        <li><a href="{{ url('/admin/branches') }}">{{ __('branches.branches') }}</a></li>
        <li class="active">{{ __('message.new_add') }}</li>
    </ol>
@endsection

@section('main-content')
    <div class="box">

        <div class="box-header ">
            <h3 class="box-title">{{ __('message.new_add') }}</h3>
            <div class="box-tools">
                <a href="{{ url('/admin/branches') }}" class="btn btn-default"><i class="fa fa-arrow-left"
                        aria-hidden="true"></i> <span class="hidden-xs">
                        {{ __('message.lists') }}</span></a>
            </div>
        </div>
        {!! Form::open(['url' => '/admin/branches', 'class' => 'form-horizontal', 'files' => true]) !!}

        @include('admin.branches.form')

        {!! Form::close() !!}
    </div>
@endsection
