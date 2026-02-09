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
            <a href="{{ route('employees.index') }}">
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
                <a href="{{ route('employees.index') }}" class="btn btn-default btn-sm">
                    <i class="fa fa-arrow-left"></i>
                    <span class="hidden-xs">{{ __('message.lists') }}</span>
                </a>

                @can('EmployeeController@update')
                    <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-primary btn-sm">
                        <i class="far fa-edit"></i>
                        <span class="hidden-xs">{{ __('message.edit') }}</span>
                    </a>
                @endcan

                @can('EmployeeController@destroy')
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
                @endcan
            </div>
        </div>

        {{-- BODY --}}
        <div class="box-body table-responsive no-padding">
            <table class="table table-bordered table-striped">
                <tbody>

                    <tr>
                        <th style="width:20%">{{ __('employees.code') }}</th>
                        <td><code>{{ $employee->code ?? '—' }}</code></td>
                    </tr>

                    <tr>
                        <th>{{ __('employees.name') }}</th>
                        <td>{{ $employee->name }}</td>
                    </tr>

                    <tr>
                        <th>{{ __('employees.avatar') }}</th>
                        <td>
                            @if (!empty($employee->avatar))
                                <img src="{{ Storage::url($employee->avatar) }}" alt="{{ $employee->name }}"
                                    style="max-width:220px;border-radius:6px;border:1px solid #ddd;">
                            @else
                                —
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>{{ __('employees.phone') }}</th>
                        <td>{{ $employee->phone ?: '—' }}</td>
                    </tr>

                    <tr>
                        <th>{{ __('employees.branch') }}</th>
                        <td>
                            {{ $employee->branch->name ?? '—' }}
                        </td>
                    </tr>

                    <tr>
                        <th>{{ __('message.active') }}</th>
                        <td>
                            @if ($employee->is_active)
                                <span class="label label-success">{{ __('message.active') }}</span>
                            @else
                                <span class="label label-default">{{ __('message.inactive') }}</span>
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>{{ __('message.created_at') }}</th>
                        <td>{{ optional($employee->created_at)->format(config('settings.format.datetime')) }}</td>
                    </tr>

                    <tr>
                        <th>{{ __('message.updated_at') }}</th>
                        <td>{{ optional($employee->updated_at)->format(config('settings.format.datetime')) }}</td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
@endsection
