@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Chi tiết bình luận
@endsection

@section('contentheader_title')
    Chi tiết bình luận
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li>
            <a href="{{ url('admin') }}">
                <i class="fa fa-home"></i> {{ __('message.dashboard') }}
            </a>
        </li>
        <li>
            <a href="{{ route('post-comments.index') }}">
                Bình luận bài viết
            </a>
        </li>
        <li class="active">{{ __('message.detail') }}</li>
    </ol>
@endsection

@section('main-content')
    <div class="box box-primary">

        {{-- Header --}}
        <div class="box-header with-border">
            <h3 class="box-title">{{ __('message.detail') }}</h3>

            <div class="box-tools pull-right">
                <a href="{{ route('post-comments.index') }}" class="btn btn-default btn-sm">
                    <i class="fa fa-arrow-left"></i> {{ __('message.lists') }}
                </a>

                {{-- Approve --}}
                @if (!$comment->is_approved)
                    {!! Form::open([
                        'method' => 'PATCH',
                        'route' => ['post-comments.approve', $comment->id],
                        'style' => 'display:inline',
                    ]) !!}
                    {!! Form::button('<i class="fa fa-check"></i> Duyệt', [
                        'type' => 'submit',
                        'class' => 'btn btn-success btn-sm',
                    ]) !!}
                    {!! Form::close() !!}
                @endif

                {{-- Spam --}}
                @if (!$comment->is_spam)
                    {!! Form::open([
                        'method' => 'PATCH',
                        'route' => ['post-comments.spam', $comment->id],
                        'style' => 'display:inline',
                    ]) !!}
                    {!! Form::button('<i class="fa fa-ban"></i> Spam', [
                        'type' => 'submit',
                        'class' => 'btn btn-warning btn-sm',
                    ]) !!}
                    {!! Form::close() !!}
                @endif

                @can('PostCommentController@destroy')
                    {!! Form::open([
                        'method' => 'DELETE',
                        'url' => ['/admin/post-comments', $comment->id],
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

        {{-- Body --}}
        <div class="box-body table-responsive no-padding">
            <table class="table table-bordered table-striped">
                <tbody>

                    {{-- Post --}}
                    <tr>
                        <th style="width:25%">Bài viết</th>
                        <td>
                            <strong>{{ $comment->post->title ?? '—' }}</strong>
                            <br>
                            @if ($comment->post)
                                <a href="{{ route('posts.show', $comment->post->slug) }}" target="_blank">
                                    <i class="fa fa-external-link"></i> Xem bài viết ngoài website
                                </a>
                            @endif
                        </td>
                    </tr>

                    {{-- Customer --}}
                    <tr>
                        <th>Người bình luận</th>
                        <td>
                            <strong>{{ $comment->customer->name ?? 'Khách' }}</strong><br>
                            <small>{{ $comment->customer->email ?? '' }}</small>
                        </td>
                    </tr>

                    {{-- Parent comment --}}
                    @if ($comment->parent)
                        <tr>
                            <th>Bình luận cha</th>
                            <td>
                                <div class="well well-sm">
                                    {!! nl2br(e($comment->parent->content)) !!}
                                </div>
                            </td>
                        </tr>
                    @endif

                    {{-- Content --}}
                    <tr>
                        <th>Nội dung bình luận</th>
                        <td>
                            <div class="well">
                                {!! nl2br(e($comment->content)) !!}
                            </div>
                        </td>
                    </tr>

                    {{-- Status --}}
                    <tr>
                        <th>Trạng thái</th>
                        <td>
                            @if ($comment->is_spam)
                                <span class="label label-danger">Spam</span>
                            @elseif($comment->is_approved)
                                <span class="label label-success">Đã duyệt</span>
                            @else
                                <span class="label label-default">Chờ duyệt</span>
                            @endif
                        </td>
                    </tr>

                    {{-- Created --}}
                    <tr>
                        <th>Thời gian</th>
                        <td>
                            {{ $comment->created_at->format(config('settings.format.datetime')) }}
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

    </div>
@endsection
