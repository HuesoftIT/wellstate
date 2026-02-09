@extends('adminlte::layouts.app')

@section('htmlheader_title')
    {{ __('employees.employee') }}
@endsection

@section('contentheader_title')
    {{ __('employees.employee') }}
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li>
            <a href="{{ url('admin') }}">
                <i class="fa fa-home"></i> {{ __('message.dashboard') }}
            </a>
        </li>
        <li>
            <a href="{{ route('employee-services.index') }}">
                {{ __('employees.employees') }}
            </a>
        </li>
        <li class="active">{{ __('message.detail') }}</li>
    </ol>
@endsection

@section('main-content')
    <div class="box box-primary">

        {{-- HEADER --}}
        <div class="box-header with-border">
            <h3 class="box-title">{{ __('message.detail') }}</h3>

            <div class="box-tools pull-right">
                <a href="{{ route('employee-services.index') }}" class="btn btn-default btn-sm">
                    <i class="fa fa-arrow-left"></i>
                    <span class="hidden-xs">{{ __('message.lists') }}</span>
                </a>

                @can('EmployeeController@update')
                    <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-primary btn-sm">
                        <i class="far fa-edit"></i>
                        <span class="hidden-xs">{{ __('message.edit') }}</span>
                    </a>
                @endcan

                {{-- @can('EmployeeController@destroy')
                    {!! Form::open([
                        'method' => 'DELETE',
                        'route' => ['employees.destroy', $employee->id],
                        'style' => 'display:inline',
                    ]) !!}
                    {!! Form::button(
                        '<i class="far fa-trash-alt"></i> <span class="hidden-xs">' . __('message.delete') . '</span>',
                        [
                            'type' => 'submit',
                            'class' => 'btn btn-danger btn-sm',
                            'onclick' => 'return confirm("' . __('message.confirm_delete') . ' ?")',
                        ],
                    ) !!}
                    {!! Form::close() !!}
                @endcan --}}
            </div>
        </div>

        {{-- BODY --}}
        <div class="box-body table-responsive no-padding">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th width="20%">{{ __('employees.code') }}</th>
                        <td><code>{{ $employee->code ?? '—' }}</code></td>
                    </tr>

                    <tr>
                        <th>{{ __('employees.name') }}</th>
                        <td>{{ $employee->name }}</td>
                    </tr>

                    <tr>
                        <th>{{ __('employees.branch') }}</th>
                        <td>{{ $employee->branch->name ?? '—' }}</td>
                    </tr>

                    <tr>
                        <th>{{ __('message.status') }}</th>
                        <td>
                            {!! $employee->is_active
                                ? '<span class="label label-success">' . __('message.active') . '</span>'
                                : '<span class="label label-default">' . __('message.inactive') . '</span>' !!}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-briefcase"></i>
                    {{ __('services.responsible_services') }}
                </h3>

                @can('EmployeeServiceController@update')
                    <div class="box-tools pull-right">
                        <a href="{{ route('employee-services.edit', $employee->id) }}" class="btn btn-success btn-sm">
                            <i class="fa fa-edit"></i> {{ __('message.update') }}
                        </a>
                    </div>
                @endcan
            </div>

            <div class="box-body">
                @if ($employee->services->isEmpty())
                    <div class="alert alert-warning mb-0">
                        <i class="fa fa-info-circle"></i>
                        {{ __('services.no_service_assigned') }}
                    </div>
                @else
                    @php
                        $groupedServices = $employee->services->groupBy('serviceCategory.name');
                    @endphp

                    <div class="row">
                        @foreach ($groupedServices as $category => $services)
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <strong>
                                            <i class="fa fa-folder-open"></i>
                                            {{ $category }}
                                        </strong>
                                    </div>
                                    <div class="panel-body">
                                        @foreach ($services as $service)
                                            <span class="label label-info"
                                                style="margin:2px 4px 4px 0; display:inline-block">
                                                {{ $service->title }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
