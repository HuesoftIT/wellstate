@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Post Detail
@endsection

@section('contentheader_title')
    Post Detail
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li>
            <a href="{{ url('admin') }}">
                <i class="fa fa-home"></i> {{ __('message.dashboard') }}
            </a>
        </li>
        <li>
            <a href="{{ url('/admin/posts') }}">
                Posts
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
                <a href="{{ url('/admin/posts') }}" class="btn btn-default btn-sm">
                    <i class="fa fa-arrow-left"></i>
                    <span class="hidden-xs">{{ __('message.lists') }}</span>
                </a>

                @can('PostController@update')
                    <a href="{{ url('/admin/posts/' . $post->id . '/edit') }}" class="btn btn-primary btn-sm">
                        <i class="far fa-edit"></i>
                        <span class="hidden-xs">{{ __('message.edit') }}</span>
                    </a>
                @endcan

                @can('PostController@destroy')
                    {!! Form::open([
                        'method' => 'DELETE',
                        'url' => ['/admin/posts', $post->id],
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
                        <th style="width:20%">Tiêu đề</th>
                        <td>{{ $post->title }}</td>
                    </tr>

                    <tr>
                        <th>Slug</th>
                        <td>{{ $post->slug }}</td>
                    </tr>

                    <tr>
                        <th>Danh mục</th>
                        <td>{{ optional($post->category)->name ?? '—' }}</td>
                    </tr>

                    <tr>
                        <th>Ảnh đại diện</th>
                        <td>
                            @if ($post->image)
                                <img src="{{ Storage::url($post->image) }}" style="max-width:200px" class="img-thumbnail">
                            @else
                                —
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Mô tả ngắn</th>
                        <td>{{ $post->excerpt ?: '—' }}</td>
                    </tr>

                    <tr>
                        <th>Nội dung</th>
                        <td>
                            {!! $post->content !!}
                        </td>
                    </tr>

                    <tr>
                        <th>Ngày đăng</th>
                        <td>
                            {{ $post->published_at ? $post->published_at->format(config('settings.format.datetime')) : 'Đăng ngay' }}
                        </td>
                    </tr>

                    <tr>
                        <th>Trạng thái</th>
                        <td>
                            @if ($post->is_active)
                                <span class="label label-success">Active</span>
                            @else
                                <span class="label label-default">Inactive</span>
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Ngày tạo</th>
                        <td>
                            {{ $post->created_at->format(config('settings.format.datetime')) }}
                        </td>
                    </tr>

                    <tr>
                        <th>Cập nhật lúc</th>
                        <td>
                            {{ $post->updated_at->format(config('settings.format.datetime')) }}
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
@endsection
