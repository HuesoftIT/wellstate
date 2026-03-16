@extends('adminlte::layouts.app')
@section('style')
    <style>
        .preview-item {
            position: relative;
            display: inline-block;
        }

        .preview-item img {
            max-height: 120px;
            border: 1px solid #ddd;
            padding: 4px;
            background: #fff;
        }

        .remove-image {
            position: absolute;
            top: -6px;
            right: -6px;
            background: red;
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            font-size: 12px;
            text-align: center;
            line-height: 20px;
            cursor: pointer;
        }
    </style>
@endsection
@section('htmlheader_title')
    {{ __('images.images') }}
@endsection
@section('contentheader_title')
    {{ __('images.images') }}
@endsection
@section('contentheader_description')
@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url('admin') }}"><i class="fa fa-home"></i> {{ __('message.dashboard') }}</a></li>
        <li><a href="{{ url('/admin/images') }}">{{ __('images.images') }}</a></li>
        <li class="active">{{ __('message.new_add') }}</li>
    </ol>
@endsection

@section('main-content')
    <div class="box">

        <div class="box-header ">
            <h3 class="box-title">{{ __('message.new_add') }}</h3>
            <div class="box-tools">
                <a href="{{ url('/admin/images') }}" class="btn btn-default"><i class="fa fa-arrow-left"
                        aria-hidden="true"></i> <span class="hidden-xs">
                        {{ __('message.lists') }}</span></a>
            </div>
        </div>
        {!! Form::open(['url' => '/admin/images', 'class' => 'form-horizontal', 'files' => true]) !!}

        @include('admin.images.form')

        {!! Form::close() !!}
    </div>
@endsection
