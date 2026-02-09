@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ __('room_types.room_types') }}
@endsection
@section('contentheader_title')
    {{ __('room_types.room_types') }}
@endsection
@section('contentheader_description')
@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url('admin') }}"><i class="fa fa-home"></i> {{ __('message.dashboard') }}</a></li>
        <li><a href="{{ url('/admin/room-types') }}">{{ __('room_types.room_types') }}</a></li>
        <li class="active">{{ __('message.detail') }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="box-header">
            <h5 class="float-left">{{ __('message.detail') }}</h5>
            <div class="box-tools">
                <a href="{{ url('/admin/room-types') }}" title="{{ __('message.lists') }}"
                    class="btn btn-default btn-sm mr-1"><i class="fa fa-arrow-left"></i> <span class="hidden-xs">
                        {{ __('message.lists') }}</span></a>
                @can('RoomTypeController@update')
                    <a href="{{ url('/admin/room-types/' . $roomType->id . '/edit') }}" class="btn btn-default btn-sm mr-1"><i
                            class="far fa-edit"></i> <span class="hidden-xs">
                            {{ __('message.edit') }}</span></a>
                @endcan
                @can('RoomTypeController@destroy')
                    {!! Form::open([
                        'method' => 'DELETE',
                        'url' => ['/admin/room-types', $roomType->id],
                        'style' => 'display:inline',
                    ]) !!}
                    {!! Form::button(
                        '<i class="far fa-trash-alt"></i> <span class="hidden-xs"> ' . __('message.delete') . '</span>',
                        [
                            'type' => 'submit',
                            'class' => 'btn btn-default btn-sm',
                            'title' => 'Xoá',
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
                        <th style="width: 15%;">{{ __('room_types.code') }}</th>
                        <td>{{ $roomType->code }}</td>
                    </tr>

                    <tr>
                        <th>{{ __('room_types.name') }}</th>
                        <td>{{ $roomType->name }}</td>
                    </tr>

                    <tr>
                        <th>{{ __('room_types.capacity') }}</th>
                        <td>{{ $roomType->capacity }}</td>
                    </tr>

                    <tr>
                        <th>{{ __('room_types.extra_fee') }}</th>
                        <td>{{ number_format($roomType->extra_fee, 0, ',', '.') }} VND</td>
                    </tr>

                    <tr>
                        <th>{{ __('room_types.is_active') }}</th>
                        <td>
                            {!! $roomType->is_active
                                ? '<span class="label label-success">' . __('message.active') . '</span>'
                                : '<span class="label label-danger">' . __('message.inactive') . '</span>' !!}
                        </td>
                    </tr>

                    <tr>
                        <th>{{ __('room_types.created_at') }}</th>
                        <td>
                            {{ \Carbon\Carbon::parse($roomType->created_at)->format(config('settings.format.datetime')) }}
                        </td>
                    </tr>

                    <tr>
                        <th>{{ __('room_types.updated_at') }}</th>
                        <td>
                            {{ \Carbon\Carbon::parse($roomType->updated_at)->format(config('settings.format.datetime')) }}
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

    </div>
@endsection
