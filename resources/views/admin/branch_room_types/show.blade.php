@extends('adminlte::layouts.app')

@section('htmlheader_title')
    {{ __('branch_room_types.branch_room_types') }}
@endsection

@section('contentheader_title')
    {{ __('branch_room_types.branch_room_types') }}
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li>
            <a href="{{ url('admin') }}">
                <i class="fa fa-home"></i> {{ __('message.dashboard') }}
            </a>
        </li>
        <li>
            <a href="{{ url('/admin/branch-room-types') }}">
                {{ __('branch_room_types.branch_room_types') }}
            </a>
        </li>
        <li class="active">{{ __('message.detail') }}</li>
    </ol>
@endsection

@section('main-content')
    <div class="box">

        {{-- HEADER --}}
        <div class="box-header with-border">
            <h5 class="box-title">{{ __('message.detail') }}</h5>

            <div class="box-tools pull-right">
                <a href="{{ url('/admin/branch-room-types') }}" class="btn btn-default btn-sm mr-1">
                    <i class="fa fa-arrow-left"></i>
                    <span class="hidden-xs">{{ __('message.lists') }}</span>
                </a>

                @can('BranchRoomTypeController@update')
                    <a href="{{ url('/admin/branch-room-types/' . $branchRoomType->id . '/edit') }}"
                        class="btn btn-default btn-sm mr-1">
                        <i class="far fa-edit"></i>
                        <span class="hidden-xs">{{ __('message.edit') }}</span>
                    </a>
                @endcan

                @can('BranchRoomTypeController@destroy')
                    {!! Form::open([
                        'method' => 'DELETE',
                        'url' => ['/admin/branch-room-types', $branchRoomType->id],
                        'style' => 'display:inline',
                    ]) !!}
                    {!! Form::button(
                        '<i class="far fa-trash-alt"></i> <span class="hidden-xs">' . __('message.delete') . '</span>',
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

        {{-- BODY --}}
        <div class="box-body table-responsive no-padding">
            <table class="table table-striped">
                <tbody>

                    {{-- BRANCH --}}
                    <tr>
                        <th style="width: 20%;">{{ __('branch_room_types.branch') }}</th>
                        <td>{{ $branchRoomType->branch->name ?? '-' }}</td>
                    </tr>

                    {{-- ROOM TYPE --}}
                    <tr>
                        <th>{{ __('branch_room_types.room_type') }}</th>
                        <td>{{ $branchRoomType->roomType->name ?? '-' }}</td>
                    </tr>

                    {{-- CAPACITY --}}
                    <tr>
                        <th>{{ __('branch_room_types.capacity') }}</th>
                        <td>{{ $branchRoomType->capacity }}</td>
                    </tr>

                    {{-- PRICE --}}
                    <tr>
                        <th>{{ __('branch_room_types.price') }}</th>
                        <td>
                            {{ number_format($branchRoomType->price, 0, ',', '.') }} VND
                        </td>
                    </tr>

                    {{-- STATUS --}}
                    <tr>
                        <th>{{ __('branch_room_types.is_active') }}</th>
                        <td>
                            {!! $branchRoomType->is_active
                                ? '<span class="label label-success">' . __('message.active') . '</span>'
                                : '<span class="label label-danger">' . __('message.inactive') . '</span>' !!}
                        </td>
                    </tr>

                    {{-- CREATED --}}
                    <tr>
                        <th>{{ __('branch_room_types.created_at') }}</th>
                        <td>
                            {{ $branchRoomType->created_at ? $branchRoomType->created_at->format(config('settings.format.datetime')) : '-' }}
                        </td>
                    </tr>

                    {{-- UPDATED --}}
                    <tr>
                        <th>{{ __('branch_room_types.updated_at') }}</th>
                        <td>
                            {{ $branchRoomType->updated_at ? $branchRoomType->updated_at->format(config('settings.format.datetime')) : '-' }}
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
@endsection
