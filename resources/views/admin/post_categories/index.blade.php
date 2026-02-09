@extends('adminlte::layouts.app')
@section('style')
    <style>
        .select2 {
            width: 250px;
        }
    </style>
@endsection
@section('htmlheader_title')
    {{ __('posts.category') }}
@endsection
@section('contentheader_title')
    {{ __('posts.category') }}
@endsection
@section('contentheader_description')
@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url('admin') }}"><i class="fa fa-home"></i> {{ __('message.dashboard') }}</a></li>
        <li class="active">{{ __('posts.category') }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="content-header border-bottom pb-5">
            <h5 class="float-left">
                {{ __('message.lists') }}
            </h5>
            @can('PostCategoryController@store')
                <a href="{{ url('/admin/post-categories/create') }}" class="btn btn-default float-right">
                    <i class="fa fa-plus-circle"></i> {{ __('message.new_add') }}
                </a>
            @endcan

        </div>

        <div class="box-header">
            <div class="box-tools">
                {!! Form::open(['method' => 'GET', 'url' => '/admin/post-categories', 'class' => 'pull-left']) !!}
                <div class="input-group" style="display:flex;">
                    <input type="text" value="{{ \Request::get('search') }}" class="form-control input-sm" name="search"
                        placeholder="{{ __('message.search_keyword') }}" style="width: 250px; margin-right: 5px;">
                    <button class="btn btn-secondary btn-sm" type="submit">
                        <i class="fa fa-search"></i> {{ __('message.search') }}
                    </button>
                </div>
                {!! Form::close() !!}


            </div>
        </div>
        @php($index = ($postCategories->currentPage() - 1) * $postCategories->perPage())

        <div class="box-body no-padding">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3.5%">
                            <input type="checkbox" id="chkAll">
                        </th>
                        <th class="text-center" style="width: 3.5%">
                            {{ trans('message.index') }}
                        </th>
                        <th>@sortablelink('name', 'Tên danh mục')</th>
                        <th class="text-center">Nội dung</th>

                        <th class="text-center">@sortablelink('order', 'Thứ tự')</th>
                        <th class="text-center" width="8%">Kích hoạt</th>
                        <th class="text-center">
                            @sortablelink('created_at', __('news.created_at'))
                        </th>
                        <th class="text-center">
                            @sortablelink('updated_at', __('news.updated_at'))
                        </th>
                        <th class="text-center" style="width: 7%"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($postCategories as $item)
                        @include('admin.post_categories._row', [
                            'item' => $item,
                            'level' => 0,
                        ])
                    @empty
                        <tr>
                            <td class="text-center" colspan="9">
                                {{ __('services.no_item') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                {{-- <tbody>
                    @forelse ($postCategories as $item)
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" name="chkId" value="{{ $item->id }}">
                            </td>

                            <td class="text-center">{{ ++$index }}</td>

                            <td>
                                @can('PostCategoryController@show')
                                    <a href="{{ route('post-categories.show', $item->id) }}" class="text-black">
                                        {{ $item->name }}
                                    </a>
                                @else
                                    {{ $item->name }}
                                @endcan
                            </td>
                            <td class="">
                                {{ $item->description }}
                            </td>
                            <td class="text-center">
                                {{ $item->order }}
                            </td>

                            <td class="text-center">
                                @if ($item->is_active)
                                    <i class="fa fa-check text-primary"></i>
                                @endif
                            </td>

                            <td class="text-center">
                                {{ $item->created_at?->format(config('settings.format.date')) }}
                            </td>

                            <td class="text-center">
                                {{ $item->updated_at?->format(config('settings.format.date')) }}
                            </td>

                            <td class="text-center dropdown">
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle"
                                    data-toggle="dropdown">
                                    <i class="fal fa-tools"></i>
                                </button>

                                <div class="dropdown-menu p-0 dropdown-menu-right">
                                    @can('PostCategoryController@show')
                                        <a class="dropdown-item btn btn-info btn-sm"
                                            href="{{ route('post-categories.show', $item->id) }}">
                                            <i class="fas fa-eye"></i> {{ __('message.view') }}
                                        </a>
                                    @endcan

                                    @can('PostCategoryController@update')
                                        <a class="dropdown-item btn btn-primary btn-sm"
                                            href="{{ route('post-categories.edit', $item->id) }}">
                                            <i class="far fa-edit"></i> {{ __('message.edit') }}
                                        </a>
                                    @endcan

                                    @can('PostCategoryController@destroy')
                                        {!! Form::open([
                                            'method' => 'DELETE',
                                            'route' => ['post-categories.destroy', $item->id],
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
                            <td class="text-center" colspan="8">
                                {{ __('services.no_item') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody> --}}
            </table>
        </div>
        <div class="box-footer clearfix">
            <div id="btn-act">
                @can('PostCategoryController@destroy')
                    <a href="#" id="deletePostCategories" data-action="deletePostCategories"
                        class="btn-act btn btn-danger btn-sm" title="{{ __('message.delete') }}">
                        <i class="fas fa-trash-alt" aria-hidden="true"></i>
                    </a>
                @endcan
                &nbsp;
                @can('PostCategoryController@active')
                    <a href="#" id="activePostCategories" data-action="activePostCategories"
                        class="btn-act btn btn-success btn-sm" title="{{ __('message.approved') }}">
                        <i class="fa fa-check" aria-hidden="true"></i>
                    </a>
                @endcan
            </div>
            <div class="page-footer pull-right">
                {!! $postCategories->appends(\Request::except('page'))->render() !!}
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
