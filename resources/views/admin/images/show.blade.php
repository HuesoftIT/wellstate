@extends('adminlte::layouts.app')

@section('htmlheader_title')
    {{ __('images.image_detail') ?? 'Image detail' }}
@endsection

@section('contentheader_title')
    {{ __('images.image_detail') ?? 'Image detail' }}
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li>
            <a href="{{ url('admin') }}">
                <i class="fa fa-home"></i> {{ __('message.dashboard') }}
            </a>
        </li>
        <li>
            <a href="{{ route('images.index') }}">
                {{ __('images.images') ?? 'Images' }}
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
                <a href="{{ route('images.index') }}" class="btn btn-default btn-sm">
                    <i class="fa fa-arrow-left"></i>
                    <span class="hidden-xs">{{ __('message.lists') }}</span>
                </a>

                @can('ImageController@update')
                    <a href="{{ route('images.edit', $image->id) }}" class="btn btn-primary btn-sm">
                        <i class="far fa-edit"></i>
                        <span class="hidden-xs">{{ __('message.edit') }}</span>
                    </a>
                @endcan

                @can('ImageController@destroy')
                    {!! Form::open([
                        'method' => 'DELETE',
                        'route' => ['images.destroy', $image->id],
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

                    {{-- CATEGORY --}}
                    <tr>
                        <th style="width:20%">{{ __('images.category') }}</th>
                        <td>
                            {{ $image->category->name ?? '—' }}
                        </td>
                    </tr>

                    {{-- TITLE --}}
                    <tr>
                        <th>{{ __('images.title') }}</th>
                        <td>{{ $image->title }}</td>
                    </tr>

                    {{-- IMAGE --}}
                    <tr>
                        <th>{{ __('images.image') }}</th>
                        <td>
                            @if ($image->image)
                                <img src="{{ Storage::url($image->image) }}" style="max-width:300px" class="img-thumbnail">
                            @else
                                —
                            @endif
                        </td>
                    </tr>

                    {{-- LINK --}}
                    <tr>
                        <th>{{ __('images.link') }}</th>
                        <td>
                            @if ($image->link)
                                <a href="{{ $image->link }}" target="_blank">
                                    {{ $image->link }}
                                </a>
                            @else
                                —
                            @endif
                        </td>
                    </tr>

                    {{-- ORDER --}}
                    <tr>
                        <th>{{ __('images.order') }}</th>
                        <td>{{ $image->order ?? '—' }}</td>
                    </tr>

                    {{-- STATUS --}}
                    <tr>
                        <th>{{ __('images.active') }}</th>
                        <td>
                            @if ($image->is_active)
                                <span class="label label-success">Active</span>
                            @else
                                <span class="label label-default">Inactive</span>
                            @endif
                        </td>
                    </tr>

                    {{-- CREATED --}}
                    <tr>
                        <th>{{ __('message.created_at') }}</th>
                        <td>{{ $image->created_at->format(config('settings.format.datetime')) }}</td>
                    </tr>

                    {{-- UPDATED --}}
                    <tr>
                        <th>{{ __('message.updated_at') }}</th>
                        <td>{{ $image->updated_at->format(config('settings.format.datetime')) }}</td>
                    </tr>

                </tbody>
            </table>
        </div>

    </div>
@endsection
