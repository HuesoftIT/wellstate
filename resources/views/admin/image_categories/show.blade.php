@extends('adminlte::layouts.app')

@section('htmlheader_title')
    {{ __('images.image_category_detail') ?? 'Image Category Detail' }}
@endsection

@section('contentheader_title')
    {{ __('images.image_category_detail') ?? 'Image Category Detail' }}
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li>
            <a href="{{ url('admin') }}">
                <i class="fa fa-home"></i> {{ __('message.dashboard') }}
            </a>
        </li>
        <li>
            <a href="{{ route('image-categories.index') }}">
                {{ __('images.image_categories') ?? 'Image Categories' }}
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
                <a href="{{ route('image-categories.index') }}" class="btn btn-default btn-sm">
                    <i class="fa fa-arrow-left"></i>
                    <span class="hidden-xs">{{ __('message.lists') }}</span>
                </a>

                @can('ImageCategoryController@update')
                    <a href="{{ route('image-categories.edit', $imageCategory->id) }}" class="btn btn-primary btn-sm">
                        <i class="far fa-edit"></i>
                        <span class="hidden-xs">{{ __('message.edit') }}</span>
                    </a>
                @endcan

                @can('ImageCategoryController@destroy')
                    {!! Form::open([
                        'method' => 'DELETE',
                        'route' => ['image-categories.destroy', $imageCategory->id],
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

                    {{-- NAME --}}
                    <tr>
                        <th style="width:20%">{{ __('images.category_name') }}</th>
                        <td>{{ $imageCategory->name }}</td>
                    </tr>

                    {{-- STATUS --}}
                    <tr>
                        <th>{{ __('images.active') }}</th>
                        <td>
                            @if ($imageCategory->is_active)
                                <span class="label label-success">{{ __('message.active') }}</span>
                            @else
                                <span class="label label-default">{{ __('message.inactive') }}</span>
                            @endif
                        </td>
                    </tr>

                    {{-- TOTAL IMAGES --}}
                    <tr>
                        <th>{{ __('images.total_images') }}</th>
                        <td>{{ $imageCategory->images->count() }}</td>
                    </tr>

                    {{-- CREATED --}}
                    <tr>
                        <th>{{ __('message.created_at') }}</th>
                        <td>{{ $imageCategory->created_at->format(config('settings.format.datetime')) }}</td>
                    </tr>

                    {{-- UPDATED --}}
                    <tr>
                        <th>{{ __('message.updated_at') }}</th>
                        <td>{{ $imageCategory->updated_at->format(config('settings.format.datetime')) }}</td>
                    </tr>

                </tbody>
            </table>
        </div>

    </div>
@endsection
