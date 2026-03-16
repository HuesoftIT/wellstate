@extends('adminlte::layouts.app')
@section('style')
    <style>
        .select2 {
            width: 250px;
        }
    </style>
@endsection
@section('htmlheader_title')
    {{ __('images.images') }}
@endsection
@section('contentheader_title')
    {{ __('images.images') }}
@endsection
@section('contentheader_description')
@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url('admin') }}"><i class="fa fa-home"></i> {{ __('message.dashboard') }}</a></li>
        <li class="active">{{ __('images.images') }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="content-header border-bottom pb-5">
            <h5 class="float-left">
                {{ __('message.lists') }}
            </h5>

            @can('ImageController@store')
                <a href="{{ route('images.create') }}" class="btn btn-default float-right">
                    <i class="fa fa-plus-circle"></i> {{ __('message.new_add') }}
                </a>
            @endcan
        </div>

        <div class="box-header">

            <div class="box-tools">

                {!! Form::open([
                    'method' => 'GET',
                    'url' => route('images.index'),
                    'class' => 'form-inline',
                ]) !!}

                <div class="input-group" style="display:flex; gap:8px; align-items:center;">

                    {{-- CATEGORY --}}
                    {!! Form::select('image_category_id', $filterCategories, request('image_category_id'), [
                        'class' => 'form-control input-sm',
                        'placeholder' => __('images.category'),
                        'style' => 'width:200px',
                    ]) !!}

                    {{-- STATUS --}}
                    {!! Form::select(
                        'is_active',
                        [
                            '1' => __('message.active'),
                            '0' => __('message.inactive'),
                        ],
                        request('is_active'),
                        [
                            'class' => 'form-control input-sm',
                            'placeholder' => __('message.status'),
                            'style' => 'width:150px',
                        ],
                    ) !!}

                 
                    {{-- SEARCH BUTTON --}}
                    <button class="btn btn-primary btn-sm" type="submit">
                        <i class="fa fa-search"></i>
                        {{ __('message.search') }}
                    </button>

                  

                </div>

                {!! Form::close() !!}

            </div>

        </div>


        @php($index = 0)

        <div class="box-body no-padding">

            @forelse ($categories as $category)
                <div class="p-2 bg-light border-bottom">
                    <strong>{{ $category->name }}</strong>
                </div>

                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" width="3%">
                                <input type="checkbox" class="chkAllCategory">
                            </th>

                            <th class="text-center" width="4%">
                                {{ __('message.index') }}
                            </th>

                            <th class="text-center">
                                {{ __('images.image') }}
                            </th>

                            <th class="text-center">
                                {{ __('images.order') }}
                            </th>

                            <th class="text-center">
                                {{ __('message.active') }}
                            </th>

                            <th>{{ __('news.created_at') }}</th>
                            <th>{{ __('news.updated_at') }}</th>

                            <th width="7%"></th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($category->images as $item)
                            <tr>

                                <td class="text-center">
                                    <input type="checkbox" name="chkId" value="{{ $item->id }}">
                                </td>

                                <td class="text-center">
                                    {{ ++$index }}
                                </td>

                                {{-- IMAGE --}}
                                <td class="text-center" style="width:120px;">
                                    @if ($item->image)
                                        <img src="{{ Storage::url($item->image) }}" style="max-width:100%; height:auto;">
                                    @endif
                                </td>

                                {{-- ORDER --}}
                                <td class="text-center">
                                    {{ $item->order ?? '-' }}
                                </td>

                                {{-- ACTIVE --}}
                                <td class="text-center">
                                    {!! $item->is_active ? '<i class="fa fa-check text-primary"></i>' : '<i class="fa fa-times text-danger"></i>' !!}
                                </td>

                                {{-- CREATED --}}
                                <td>
                                    {{ $item->created_at->format(config('settings.format.date')) }}
                                </td>

                                {{-- UPDATED --}}
                                <td>
                                    {{ $item->updated_at->format(config('settings.format.date')) }}
                                </td>

                                {{-- ACTION --}}
                                <td class="dropdown">

                                    <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        <i class="fal fa-tools"></i>
                                    </button>

                                    <div class="dropdown-menu p-0">

                                        @can('ImageController@show')
                                            <a href="{{ route('images.show', $item->id) }}"
                                                class="btn btn-info btn-sm dropdown-item">
                                                <i class="fas fa-eye"></i>
                                                {{ __('message.view') }}
                                            </a>
                                        @endcan

                                        @can('ImageController@update')
                                            <a href="{{ route('images.edit', $item->id) }}"
                                                class="btn btn-primary btn-sm dropdown-item">
                                                <i class="far fa-edit"></i>
                                                {{ __('message.edit') }}
                                            </a>
                                        @endcan

                                        @can('ImageController@destroy')
                                            {!! Form::open([
                                                'method' => 'DELETE',
                                                'route' => ['images.destroy', $item->id],
                                                'style' => 'display:inline',
                                            ]) !!}

                                            {!! Form::button('<i class="far fa-trash-alt"></i> ' . __('message.delete'), [
                                                'type' => 'submit',
                                                'class' => 'btn btn-danger btn-sm dropdown-item show_confirm',
                                            ]) !!}

                                            {!! Form::close() !!}
                                        @endcan

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8" class="text-center">
                                    {{ __('images.no_item') }}
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>

            @empty

                <div class="text-center p-4">
                    {{ __('images.no_item') }}
                </div>
            @endforelse

        </div>

        {{-- FOOTER --}}
        <div class="box-footer clearfix">
            <div id="btn-act">
                @can('ImageController@destroy')
                    <a href="#" id="deleteImages" data-action="deleteImages" class="btn-act btn btn-danger btn-sm"
                        title="{{ __('message.delete') }}">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                @endcan
                &nbsp;
                @can('ImageController@active')
                    <a href="#" id="activeImages" data-action="activeImages" class="btn-act btn btn-success btn-sm"
                        title="{{ __('message.approved') }}">
                        <i class="fa fa-check"></i>
                    </a>
                @endcan
            </div>

            <div class="page-footer pull-right">
                {!! $categories->appends(Request::except('page'))->render() !!}
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
                case 'activeImages':
                    actTxt = 'duyệt';
                    classAlert = 'alert-success';
                    break;
                case 'deleteImages':
                    actTxt = 'xóa';
                    classAlert = 'alert-danger';
                    break;
            }
            if (chkId.length != 0) {
                swal({
                        title: 'Bạn có muốn ' + actTxt +
                            ' hình ảnh này không?',
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
                swal("Lỗi!", 'Vui lòng chọn hình ảnh để  ' + actTxt + '!', "error")
            }
        }
    </script>
@endsection
