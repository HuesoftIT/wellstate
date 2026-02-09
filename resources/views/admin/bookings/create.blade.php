@extends('adminlte::layouts.app')

@section('htmlheader_title')
    {{ __('bookings.booking') }}
@endsection

@section('contentheader_title')
    {{ __('bookings.booking') }}
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
            <a href="{{ url('/admin/bookings') }}">
                {{ __('bookings.booking') }}
            </a>
        </li>
        <li class="active">
            {{ __('bookings.new_booking') }}
        </li>
    </ol>
@endsection

@section('main-content')
    <div class="box">

        <div class="box-header">
            <h3 class="box-title">
                {{ __('bookings.new_booking') }}
            </h3>

            <div class="box-tools">
                <a href="{{ url('/admin/bookings') }}" class="btn btn-default">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                    <span class="hidden-xs">
                        {{ __('message.lists') }}
                    </span>
                </a>
            </div>
        </div>

        {!! Form::open([
            'url' => '/admin/bookings',
            'method' => 'POST',
            'class' => 'form-horizontal',
        ]) !!}

        @include('admin.bookings.form')

        {!! Form::close() !!}
    </div>
@endsection
