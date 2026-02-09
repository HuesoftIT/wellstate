@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Post Category
@endsection

@section('contentheader_title')
    Post Category
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li>
            <a href="{{ url('admin') }}">
                <i class="fa fa-home"></i> {{ __('message.dashboard') }}
            </a>
        </li>
        <li>
            <a href="{{ url('/admin/post-categories') }}">
                Post Categories
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
        <div class="box-header with-border">
            <h3 class="box-title">{{ __('message.detail') }}</h3>

            <div class="box-tools pull-right">
                <a href="{{ url('/admin/post-categories') }}" class="btn btn-default btn-sm">
                    <i class="fa fa-arrow-left"></i>
                    <span class="hidden-xs">{{ __('message.lists') }}</span>
                </a>

                @can('PostCategoryController@update')
                    <a href="{{ url('/admin/post-categories/' . $postCategory->id . '/edit') }}" class="btn btn-primary btn-sm">
                        <i class="far fa-edit"></i>
                        <span class="hidden-xs">{{ __('message.edit') }}</span>
                    </a>
                @endcan

                @can('PostCategoryController@destroy')
                    {!! Form::open([
                        'method' => 'DELETE',
                        'url' => ['/admin/post-categories', $postCategory->id],
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
                            'onclick' => 'return confirm("' . __('message.confirm_delete') . '?")',
                        ],
                    ) !!}
                    {!! Form::close() !!}
                @endcan
            </div>
        </div>

        <div class="box-body table-responsive no-padding">
            <table class="table table-bordered table-striped">
                <tbody>

                    {{-- NAME --}}
                    <tr>
                        <th style="width: 20%">Tên danh mục</th>
                        <td>{{ $postCategory->name }}</td>
                    </tr>

                    {{-- PARENT --}}
                    <tr>
                        <th>Danh mục cha</th>
                        <td>
                            @if ($postCategory->parent)
                                <a href="{{ route('post-categories.show', $postCategory->parent->id) }}">
                                    {{ $postCategory->parent->name }}
                                </a>
                            @else
                                —
                            @endif
                        </td>
                    </tr>

                    {{-- SLUG --}}
                    <tr>
                        <th>Slug</th>
                        <td>{{ $postCategory->slug }}</td>
                    </tr>

                    {{-- DESCRIPTION --}}
                    <tr>
                        <th>Mô tả</th>
                        <td>{{ $postCategory->description ?: '—' }}</td>
                    </tr>

                    {{-- ORDER --}}
                    <tr>
                        <th>Thứ tự</th>
                        <td>{{ $postCategory->order }}</td>
                    </tr>

                    {{-- STATUS --}}
                    <tr>
                        <th>Trạng thái</th>
                        <td>
                            @if ($postCategory->is_active)
                                <span class="label label-success">Active</span>
                            @else
                                <span class="label label-default">Inactive</span>
                            @endif
                        </td>
                    </tr>

                    {{-- CHILDREN --}}
                    <tr>
                        <th>Danh mục con</th>
                        <td>
                            @if ($postCategory->children && $postCategory->children->count())
                                <ul class="list-unstyled" style="margin-bottom: 0">
                                    @foreach ($postCategory->children as $child)
                                        <li>
                                            └─
                                            <a href="{{ route('post-categories.show', $child->id) }}">
                                                {{ $child->name }}
                                            </a>

                                            @if (!$child->is_active)
                                                <span class="label label-default">Inactive</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                —
                            @endif
                        </td>
                    </tr>

                    {{-- UPDATED AT --}}
                    <tr>
                        <th>Cập nhật lúc</th>
                        <td>
                            {{ Carbon::parse($postCategory->updated_at)->format(config('settings.format.datetime')) }}
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
@endsection
