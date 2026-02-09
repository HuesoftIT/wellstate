@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Membership Detail
@endsection

@section('contentheader_title')
    Membership Detail
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li>
            <a href="{{ url('admin') }}">
                <i class="fa fa-home"></i> {{ __('message.dashboard') }}
            </a>
        </li>
        <li>
            <a href="{{ url('/admin/memberships') }}">
                Membership
            </a>
        </li>
        <li class="active">{{ __('message.detail') }}</li>
    </ol>
@endsection

@php
    use Carbon\Carbon;
@endphp

@section('main-content')
    <div class="box box-primary">

        {{-- HEADER --}}
        <div class="box-header with-border">
            <h3 class="box-title">{{ __('message.detail') }}</h3>

            <div class="box-tools pull-right">
                <a href="{{ route('memberships.index') }}" class="btn btn-default btn-sm">
                    <i class="fa fa-arrow-left"></i>
                    <span class="hidden-xs">{{ __('message.lists') }}</span>
                </a>

                @can('MembershipController@update')
                    <a href="{{ route('memberships.edit', $membership->id) }}" class="btn btn-primary btn-sm">
                        <i class="far fa-edit"></i>
                        <span class="hidden-xs">{{ __('message.edit') }}</span>
                    </a>
                @endcan

                @can('MembershipController@destroy')
                    {!! Form::open([
                        'method' => 'DELETE',
                        'route' => ['memberships.destroy', $membership->id],
                        'style' => 'display:inline',
                    ]) !!}
                    {!! Form::button(
                        '<i class="far fa-trash-alt"></i>
                                                                <span class="hidden-xs">' .
                            __('message.delete') .
                            '</span>',
                        [
                            'type' => 'submit',
                            'class' => 'btn btn-danger btn-sm',
                            'onclick' => 'return confirm("' . __('message.confirm_delete') . ' ?")',
                        ],
                    ) !!}
                    {!! Form::close() !!}
                @endcan
            </div>
        </div>

        {{-- BODY --}}
        <div class="box-body table-responsive no-padding">
            <table class="table table-bordered table-striped">
                <tbody>

                    <tr>
                        <th style="width:25%">Mã membership</th>
                        <td>{{ $membership->code }}</td>
                    </tr>

                    <tr>
                        <th>Tên membership</th>
                        <td>{{ $membership->name }}</td>
                    </tr>

                    <tr>
                        <th>Chi tiêu tối thiểu</th>
                        <td>
                            <strong>
                                {{ number_format($membership->min_total_spent) }} đ
                            </strong>
                        </td>
                    </tr>

                    <tr>
                        <th>Độ ưu tiên</th>
                        <td>{{ $membership->priority }}</td>
                    </tr>

                    <tr>
                        <th>Mô tả</th>
                        <td>{{ $membership->description ?: '—' }}</td>
                    </tr>

                    <tr>
                        <th>Trạng thái</th>
                        <td>
                            @if ($membership->is_active)
                                <span class="label label-success">Active</span>
                            @else
                                <span class="label label-default">Inactive</span>
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Ngày tạo</th>
                        <td>
                            {{ $membership->created_at->format(config('settings.format.datetime')) }}
                        </td>
                    </tr>

                    <tr>
                        <th>Cập nhật lúc</th>
                        <td>
                            {{ $membership->updated_at->format(config('settings.format.datetime')) }}
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
@endsection
