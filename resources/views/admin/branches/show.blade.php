@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Chi nhánh
@endsection

@section('contentheader_title')
    Chi nhánh
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li>
            <a href="{{ url('admin') }}">
                <i class="fa fa-home"></i> {{ __('message.dashboard') }}
            </a>
        </li>
        <li>
            <a href="{{ route('branches.index') }}">
                Chi nhánh
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
                <a href="{{ route('branches.index') }}" class="btn btn-default btn-sm">
                    <i class="fa fa-arrow-left"></i>
                    <span class="hidden-xs">{{ __('message.lists') }}</span>
                </a>

                @can('BranchController@update')
                    <a href="{{ route('branches.edit', $branch->id) }}" class="btn btn-primary btn-sm">
                        <i class="far fa-edit"></i>
                        <span class="hidden-xs">{{ __('message.edit') }}</span>
                    </a>
                @endcan

                @can('BranchController@destroy')
                    {!! Form::open([
                        'method' => 'DELETE',
                        'route' => ['branches.destroy', $branch->id],
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
                        <th style="width:20%">Mã chi nhánh</th>
                        <td><code>{{ $branch->code }}</code></td>
                    </tr>

                    <tr>
                        <th>Tên chi nhánh</th>
                        <td>{{ $branch->name }}</td>
                    </tr>
                    <tr>
                        <th>Hình ảnh</th>
                        <td>
                            @if (!empty($branch->image))
                                <img src="{{ Storage::url($branch->image) }}" alt="{{ $branch->name }}"
                                    style="max-width:220px; border-radius:4px; border:1px solid #ddd;">
                            @else
                                —
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Số điện thoại</th>
                        <td>{{ $branch->phone ?: '—' }}</td>
                    </tr>

                    <tr>
                        <th>Địa chỉ</th>
                        <td>{{ $branch->address ?: '—' }}</td>
                    </tr>

                    <tr>
                        <th>Vĩ độ</th>
                        <td>{{ $branch->latitude ?? '—' }}</td>
                    </tr>

                    <tr>
                        <th>Kinh độ</th>
                        <td>{{ $branch->longitude ?? '—' }}</td>
                    </tr>

                    <tr>
                        <th>Giờ hoạt động</th>
                        <td>
                            @if ($branch->open_time && $branch->close_time)
                                {{ \Carbon\Carbon::parse($branch->open_time)->format('H:i') }}
                                -
                                {{ \Carbon\Carbon::parse($branch->close_time)->format('H:i') }}
                            @else
                                —
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Trạng thái</th>
                        <td>
                            @if ($branch->is_active)
                                <span class="label label-success">Hoạt động</span>
                            @else
                                <span class="label label-default">Ngưng hoạt động</span>
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Ghi chú</th>
                        <td>{{ $branch->note ?: '—' }}</td>
                    </tr>

                    <tr>
                        <th>{{ __('message.created_at') }}</th>
                        <td>{{ optional($branch->created_at)->format(config('settings.format.datetime')) }}</td>
                    </tr>

                    <tr>
                        <th>{{ __('message.updated_at') }}</th>
                        <td>{{ optional($branch->updated_at)->format(config('settings.format.datetime')) }}</td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
@endsection
