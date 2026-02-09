@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Service Category
@endsection

@section('contentheader_title')
    Service Category
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li>
            <a href="{{ url('admin') }}">
                <i class="fa fa-home"></i> {{ __('message.dashboard') }}
            </a>
        </li>
        <li>
            <a href="{{ url('/admin/service-categories') }}">
                Service Categories
            </a>
        </li>
        <li class="active">{{ __('message.detail') }}</li>
    </ol>
@endsection

@section('main-content')
    <div class="box">
        <div class="box-header">
            <h5 class="float-left">{{ __('message.detail') }}</h5>
            <div class="box-tools">
                <a href="{{ url('/admin/service-categories') }}" class="btn btn-default btn-sm mr-1">
                    <i class="fa fa-arrow-left"></i>
                    <span class="hidden-xs">{{ __('message.lists') }}</span>
                </a>

                @can('ServiceCategoryController@update')
                    <a href="{{ url('/admin/service-categories/' . $serviceCategory->id . '/edit') }}"
                        class="btn btn-default btn-sm mr-1">
                        <i class="far fa-edit"></i>
                        <span class="hidden-xs">{{ __('message.edit') }}</span>
                    </a>
                @endcan

                @can('ServiceCategoryController@destroy')
                    {!! Form::open([
                        'method' => 'DELETE',
                        'url' => ['/admin/service-categories', $serviceCategory->id],
                        'style' => 'display:inline',
                    ]) !!}
                    {!! Form::button(
                        '<i class="far fa-trash-alt"></i>
                                        <span class="hidden-xs">' .
                            __('message.delete') .
                            '</span>',
                        [
                            'type' => 'submit',
                            'class' => 'btn btn-default btn-sm',
                            'onclick' => 'return confirm("' . __('message.confirm_delete') . '?")',
                        ],
                    ) !!}
                    {!! Form::close() !!}
                @endcan
            </div>
        </div>

        <div class="box-body table-responsive no-padding">
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <th style="width: 20%">Tên danh mục</th>
                        <td>{{ $serviceCategory->name }}</td>
                    </tr>

                    <tr>
                        <th>Slug</th>
                        <td>{{ $serviceCategory->slug }}</td>
                    </tr>

                    <tr>
                        <th>Mô tả</th>
                        <td>{{ $serviceCategory->description }}</td>
                    </tr>

                    <tr>
                        <th>Thứ tự</th>
                        <td>{{ $serviceCategory->order }}</td>
                    </tr>

                    <tr>
                        <th>Kích hoạt</th>
                        <td>
                            {!! $serviceCategory->is_active
                                ? '<span class="label label-success">Active</span>'
                                : '<span class="label label-default">Inactive</span>' !!}
                        </td>
                    </tr>

                    <tr>
                        <th>Cập nhật</th>
                        <td>
                            {{ Carbon\Carbon::parse($serviceCategory->updated_at)->format(config('settings.format.datetime')) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
