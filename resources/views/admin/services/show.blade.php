@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Service
@endsection

@section('contentheader_title')
    Service
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li>
            <a href="{{ url('admin') }}">
                <i class="fa fa-home"></i> {{ __('message.dashboard') }}
            </a>
        </li>
        <li>
            <a href="{{ url('/admin/services') }}">
                Services
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
                <a href="{{ url('/admin/services') }}" class="btn btn-default btn-sm mr-1">
                    <i class="fa fa-arrow-left"></i>
                    <span class="hidden-xs">{{ __('message.lists') }}</span>
                </a>

                @can('ServiceController@update')
                    <a href="{{ url('/admin/services/' . $service->id . '/edit') }}" class="btn btn-default btn-sm mr-1">
                        <i class="far fa-edit"></i>
                        <span class="hidden-xs">{{ __('message.edit') }}</span>
                    </a>
                @endcan

                @can('ServiceController@destroy')
                    {!! Form::open([
                        'method' => 'DELETE',
                        'url' => ['/admin/services', $service->id],
                        'style' => 'display:inline',
                    ]) !!}
                    {!! Form::button('<i class="far fa-trash-alt"></i><span class="hidden-xs">' . __('message.delete') . '</span>', [
                        'type' => 'submit',
                        'class' => 'btn btn-default btn-sm',
                        'onclick' => 'return confirm("' . __('message.confirm_delete') . '?")',
                    ]) !!}
                    {!! Form::close() !!}
                @endcan
            </div>
        </div>

        <div class="box-body table-responsive no-padding">
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <th style="width: 20%">Tên dịch vụ</th>
                        <td>{{ $service->name }}</td>
                    </tr>

                    <tr>
                        <th>Slug</th>
                        <td>{{ $service->slug }}</td>
                    </tr>

                    <tr>
                        <th>Danh mục</th>
                        <td>{{ optional($service->serviceCategory)->name }}</td>
                    </tr>

                    <tr>
                        <th>Mô tả</th>
                        <td>{{ $service->description ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Thời lượng</th>
                        <td>{{ $service->duration }} phút</td>
                    </tr>

                    <tr>
                        <th>Giá</th>
                        <td>{{ number_format($service->price) }} đ</td>
                    </tr>

                    <tr>
                        <th>Giá khuyến mãi</th>
                        <td>{{ $service->sale_price ? number_format($service->sale_price) . ' đ' : '-' }}</td>
                    </tr>

                    <tr>
                        <th>Combo</th>
                        <td>
                            {!! $service->is_combo
                                ? '<span class="label label-info">Combo</span>'
                                : '<span class="label label-default">Single</span>' !!}
                        </td>
                    </tr>

                    <tr>
                        <th>Trạng thái</th>
                        <td>
                            {!! $service->is_active
                                ? '<span class="label label-success">Active</span>'
                                : '<span class="label label-default">Inactive</span>' !!}
                        </td>
                    </tr>

                    <tr>
                        <th>Hình ảnh</th>
                        <td>
                            @if ($service->image)
                                <img src="{{ asset('storage/' . $service->image) }}" style="max-width: 200px"
                                    class="img-thumbnail">
                            @else
                                -
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Cập nhật</th>
                        <td>{{ $service->updated_at->format(config('settings.format.datetime')) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Hiển thị combo items nếu là combo --}}
        @if ($service->is_combo && $service->comboItems->count())
            <div class="box">
                <div class="box-header">
                    <h5>Các dịch vụ trong combo</h5>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tên dịch vụ</th>
                                <th>Số lượng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($service->comboItems as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ optional($item->service)->name ?? '---' }}</td>
                                    <td>{{ $item->quantity }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>
@endsection
