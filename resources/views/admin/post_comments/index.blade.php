@extends('adminlte::layouts.app')
@section('style')
    <style>
        .select2 {
            width: 250px;
        }
    </style>
@endsection
@section('htmlheader_title')
    {{ __('posts.comments') }}
@endsection
@section('contentheader_title')
    {{ __('posts.comments') }}
@endsection
@section('contentheader_description')
@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url('admin') }}"><i class="fa fa-home"></i> {{ __('message.dashboard') }}</a></li>
        <li class="active">{{ __('posts.comments') }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Danh sách bình luận</h3>
        </div>

        <div class="box-body table-responsive no-padding">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th width="3%">#</th>
                        <th>Người gửi</th>
                        <th>Nội dung</th>
                        <th>Bài viết</th>
                        <th width="10%" class="text-center">Trạng thái</th>
                        <th width="15%" class="text-center">Hành động</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($comments as $index => $comment)
                        <tr>
                            <td class="text-center">
                                {{ $comments->firstItem() + $index }}
                            </td>

                            {{-- Người gửi --}}
                            <td>
                                {{ $comment->customer->name ?? 'Guest' }}
                            </td>

                            {{-- Nội dung --}}
                            <td style="max-width: 350px;">
                                {{ Str::limit($comment->content, 120) }}

                                @if ($comment->parent_id)
                                    <span class="label label-info">Reply</span>
                                @endif
                            </td>

                            {{-- Link bài viết --}}
                            <td>
                                @if ($comment->post)
                                    <a href="{{ route('posts.show', $comment->post->slug) }}" target="_blank">
                                        {{ $comment->post->title }}
                                    </a>
                                @else
                                    <span class="text-muted">Bài viết đã xóa</span>
                                @endif
                            </td>

                            {{-- Trạng thái --}}
                            <td class="text-center">
                                @if ($comment->is_spam)
                                    <span class="label label-danger">Spam</span>
                                @elseif ($comment->is_approved)
                                    <span class="label label-success">Đã duyệt</span>
                                @else
                                    <span class="label label-warning">Chờ duyệt</span>
                                @endif
                            </td>

                            {{-- Action --}}
                            <td class="text-center dropdown">
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle"
                                    data-toggle="dropdown">
                                    <i class="fal fa-tools"></i>
                                </button>

                                <div class="dropdown-menu p-0 dropdown-menu-right">

                                    {{-- View --}}
                                    @can('PostCommentController@show')
                                        <a class="dropdown-item btn btn-info btn-sm"
                                            href="{{ route('post-comments.show', $comment->id) }}">
                                            <i class="fas fa-eye"></i> {{ __('message.view') }}
                                        </a>
                                    @endcan

                                    {{-- Approve --}}
                                    @can('PostCommentController@approve')
                                        {!! Form::open([
                                            'method' => 'PATCH',
                                            'route' => ['post-comments.approve', $comment->id],
                                            'style' => 'display:inline',
                                        ]) !!}
                                        {!! Form::button('<i class="fas fa-check"></i> ' . __('message.approved'), [
                                            'type' => 'submit',
                                            'class' => 'dropdown-item btn btn-success btn-sm',
                                        ]) !!}
                                        {!! Form::close() !!}
                                    @endcan

                                    {{-- Spam --}}
                                    @can('PostCommentController@spam')
                                        {!! Form::open([
                                            'method' => 'PATCH',
                                            'route' => ['post-comments.spam', $comment->id],
                                            'style' => 'display:inline',
                                        ]) !!}
                                        {!! Form::button('<i class="fas fa-ban"></i> ' . __('message.spam'), [
                                            'type' => 'submit',
                                            'class' => 'dropdown-item btn btn-warning btn-sm show_confirm',
                                        ]) !!}
                                        {!! Form::close() !!}
                                    @endcan

                                    {{-- Delete --}}
                                    @can('PostCommentController@destroy')
                                        {!! Form::open([
                                            'method' => 'DELETE',
                                            'route' => ['post-comments.destroy', $comment->id],
                                            'style' => 'display:inline',
                                        ]) !!}
                                        {!! Form::button('<i class="far fa-trash-alt"></i> ' . __('message.delete'), [
                                            'type' => 'submit',
                                            'class' => 'dropdown-item btn btn-danger btn-sm show_confirm',
                                        ]) !!}
                                        {!! Form::close() !!}
                                    @endcan

                                </div>
                            </td>


                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                Không có bình luận
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="box-footer clearfix">
            <div class="pull-right">
                {!! $comments->links() !!}
            </div>
        </div>
    </div>
    @include('sweetalert::alert')
@endsection
@section('scripts-footer')
    @toastr_js
    @toastr_render
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>

    <script type="text/javascript">
        $(function() {
            $('#chkAll').on('click', function() {
                $("input:checkbox").prop('checked', $(this).prop("checked"));
            });
        });
        $('#btn-act').on('click', '.btn-act', function(e) {
            e.preventDefault();
            let action = $(this).data('action');
            console.log(action);
            ajaxCategory(action);
        });

        function ajaxCategory(action) {
            let chkId = $("input[name='chkId']:checked");
            let actTxt = '',
                successAlert = '',
                classAlert = '';
            switch (action) {
                case 'activePostCategories':
                    actTxt = 'duyệt';
                    classAlert = 'alert-success';
                    break;
                case 'deletePostCategories':
                    actTxt = 'xóa';
                    classAlert = 'alert-danger';
                    break;
            }
            if (chkId.length != 0) {
                swal({
                        title: 'Bạn có muốn ' + actTxt +
                            ' loại bài viết này không?',
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            var arrId = '';
                            $("input[name='chkId']:checked").map((val, key) => {
                                arrId += key.value + ',';
                            });
                            axios.get('{{ url('/ajax') }}/' + action, {
                                    params: {
                                        ids: arrId
                                    }
                                })
                                .then((response) => {
                                    if (response.data.success === 'ok') {
                                        location.reload(true);
                                    }
                                })
                                .catch((error) => {})
                        }
                    });
            } else {
                swal("Lỗi!", 'Vui lòng chọn loại bài viết để  ' + actTxt + '!', "error")
            }
        }
    </script>
@endsection
