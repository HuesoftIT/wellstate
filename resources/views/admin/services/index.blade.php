@extends('adminlte::layouts.app')
@section('style')
    <style>
        .select2 {
            width: 250px;
        }
    </style>
@endsection
@section('htmlheader_title')
    {{ __('services.services') }}
@endsection
@section('contentheader_title')
    {{ __('services.services') }}
@endsection
@section('contentheader_description')
@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url('admin') }}"><i class="fa fa-home"></i> {{ __('message.dashboard') }}</a></li>
        <li class="active">{{ __('services.services') }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="content-header border-bottom pb-5">
            <h5 class="float-left">
                {{ __('message.lists') }}
            </h5>
            @can('ServiceController@store')
                <a href="{{ url('/admin/services/create') }}" class="btn btn-default float-right">
                    <i class="fa fa-plus-circle"></i> {{ __('message.new_add') }}
                </a>
            @endcan

        </div>

        <div class="box-header">
            {!! Form::open(['method' => 'GET', 'url' => route('services.index'), 'class' => 'pull-left']) !!}
            <div class="input-group" style="display:flex; gap:5px">

                {{-- Search --}}
                <input type="text" name="search" value="{{ request('search') }}" class="form-control input-sm"
                    placeholder="{{ __('message.search_keyword') }}" style="width:100px">

                {{-- Category --}}
                {!! Form::select('service_category_id', $categories, request('service_category_id'), [
                    'class' => 'form-control input-sm select2',
                    'placeholder' => __('services.category'),
                ]) !!}

                {{-- Status --}}
                {!! Form::select(
                    'is_active',
                    ['1' => __('message.active'), '0' => __('message.inactive')],
                    request('is_active'),
                    ['class' => 'form-control input-sm', 'placeholder' => __('message.status')],
                ) !!}

                <button class="btn btn-secondary btn-sm" type="submit">
                    <i class="fa fa-search"></i> {{ __('message.search') }}
                </button>
            </div>
            {!! Form::close() !!}
        </div>
        @php($index = ($services->currentPage() - 1) * $services->perPage())

        <div class="box-body no-padding">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center" width="3%">
                            <input type="checkbox" id="chkAll">
                        </th>
                        <th class="text-center" width="4%">{{ __('message.index') }}</th>
                        <th>@sortablelink('name', __('services.name'))</th>
                        <th>{{ __('services.category') }}</th>
                        <th>{{ __('services.image') }}</th>
                        <th class="text-right">{{ __('services.price') }}</th>
                        <th class="text-right">{{ __('services.sale_price') }}</th>
                        <th class="text-center">{{ __('services.duration') }}</th>
                        <th class="text-center">{{ __('services.is_combo') }}</th>
                        <th class="text-center">{{ __('message.active') }}</th>
                        <th>@sortablelink('created_at', __('news.created_at'))</th>
                        <th>@sortablelink('updated_at', __('news.updated_at'))</th>
                        <th width="7%"></th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($services as $item)
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" name="chkId" value="{{ $item->id }}">
                            </td>
                            <td class="text-center">{{ ++$index }}</td>

                            <td>
                                @can('ServiceController@show')
                                    <a href="{{ route('services.show', $item->id) }}" style="color:blue">
                                        {{ $item->title }}
                                    </a>
                                @else
                                    {{ $item->title }}
                                @endcan
                            </td>

                            <td>{{ optional($item->serviceCategory)->name }}</td>
                            <td class="text-center" style="width: 100px;">
                                <img src="{{ Storage::url($item->image) }}" alt="{{ $item->title }}"
                                    style="max-width: 100%; height: auto;">
                            </td>

                            <td class="text-right">
                                {{ number_format($item->price) }}
                            </td>
                              <td class="text-right">
                                {{ number_format($item->sale_price) ?? "Không giảm giá" }}
                            </td>

                            <td class="text-center">{{ $item->duration }} phút</td>
                            <td class="text-center">
                                {!! $item->is_combo ? '<i class="fa fa-check text-primary"></i>' : '' !!}
                            </td>

                            <td class="text-center">
                                {!! $item->is_active ? '<i class="fa fa-check text-primary"></i>' : '' !!}
                            </td>

                            <td>{{ $item->created_at->format(config('settings.format.date')) }}</td>
                            <td>{{ $item->updated_at->format(config('settings.format.date')) }}</td>

                            {{-- Action --}}
                            <td class="dropdown">
                                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                    <i class="fal fa-tools"></i>
                                </button>
                                <div class="dropdown-menu p-0">
                                    @can('ServiceController@show')
                                        <a href="{{ url('/admin/services/' . $item->id) }}"
                                            title="{{ __('message.user.view_user') }}"><button
                                                class="btn btn-info btn-sm dropdown-item"><i class="fas fa-eye"></i>
                                                {{ __('message.view') }}</button></a>
                                    @endcan
                                    @can('ServiceController@update')
                                        <a href="{{ url('/admin/services/' . $item->id . '/edit') }}"
                                            title="{{ __('message.user.edit_user') }}"><button
                                                class="btn btn-primary btn-sm dropdown-item"><i class="far fa-edit"
                                                    aria-hidden="true"></i>
                                                {{ __('message.edit') }}</button></a>
                                    @endcan
                                    @can('ServiceController@destroy')
                                        {!! Form::open([
                                            'method' => 'DELETE',
                                            'url' => ['/admin/services', $item->id],
                                            'style' => 'display:inline',
                                        ]) !!}
                                        {!! Form::button('<i class="far fa-trash-alt" aria-hidden="true"></i> ' . __('message.delete'), [
                                            'type' => 'submit',
                                            'class' => 'btn btn-danger btn-sm dropdown-item show_confirm',
                                            'title' => __('message.user.delete_user'),
                                            // 'onclick'=>'return confirm("'.__('message.confirm_delete').'")'
                                        ]) !!}
                                        {!! Form::close() !!}
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">
                                {{ __('services.no_item') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="box-footer clearfix">
            <div id="btn-act">
                @can('ServiceController@destroy')
                    <a href="#" id="deleteServices" data-action="deleteServices" class="btn-act btn btn-danger btn-sm"
                        title="{{ __('message.delete') }}">
                        <i class="fas fa-trash-alt" aria-hidden="true"></i>
                    </a>
                @endcan
                &nbsp;
                @can('ServiceController@active')
                    <a href="#" id="activeServices" data-action="activeServices" class="btn-act btn btn-success btn-sm"
                        title="{{ __('message.approved') }}">
                        <i class="fa fa-check" aria-hidden="true"></i>
                    </a>
                @endcan
            </div>
            <div class="page-footer pull-right">
                {!! $services->appends(\Request::except('page'))->render() !!}
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
                case 'activeServices':
                    actTxt = 'duyệt';
                    classAlert = 'alert-success';
                    break;
                case 'deleteServices':
                    actTxt = 'xóa';
                    classAlert = 'alert-danger';
                    break;
            }
            if (chkId.length != 0) {
                swal({
                        title: 'Bạn có muốn ' + actTxt +
                            ' loại dịch vụ này không?',
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
                swal("Lỗi!", 'Vui lòng chọn tin tức để  ' + actTxt + '!', "error")
            }
        }
    </script>
@endsection
