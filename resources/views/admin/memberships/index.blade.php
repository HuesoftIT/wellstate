@extends('adminlte::layouts.app')

@section('htmlheader_title')
    {{ __('Membership') }}
@endsection

@section('contentheader_title')
    {{ __('Membership') }}
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li>
            <a href="{{ url('admin') }}">
                <i class="fa fa-home"></i> {{ __('message.dashboard') }}
            </a>
        </li>
        <li class="active">{{ __('Membership') }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        {{-- HEADER --}}
        <div class="content-header border-bottom pb-5">
            <h5 class="float-left">
                {{ __('message.lists') }}
            </h5>

            @can('MembershipController@store')
                <a href="{{ route('memberships.create') }}" class="btn btn-default float-right">
                    <i class="fa fa-plus-circle"></i> {{ __('message.new_add') }}
                </a>
            @endcan
        </div>

        {{-- FILTER --}}
        <div class="box-header">
            <div class="box-tools">
                {!! Form::open(['method' => 'GET', 'route' => 'memberships.index', 'class' => 'pull-left']) !!}
                <div class="input-group" style="display:flex; gap:8px;">
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

                    <input type="text" name="search" value="{{ request('search') }}" class="form-control input-sm"
                        placeholder="{{ __('message.search_keyword') }}" style="width:250px">

                    <button class="btn btn-secondary btn-sm" type="submit">
                        <i class="fa fa-search"></i> {{ __('message.search') }}
                    </button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>

        @php($index = ($memberships->currentPage() - 1) * $memberships->perPage())

        {{-- TABLE --}}
        <div class="box-body no-padding">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center" width="3%">
                            <input type="checkbox" id="chkAll">
                        </th>
                        <th class="text-center" width="4%">{{ __('message.index') }}</th>
                        <th>{{ __('Code') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th class="text-right">{{ __('Min Total Spent') }}</th>
                        <th class="text-center">{{ __('Priority') }}</th>
                        <th class="text-center">{{ __('message.active') }}</th>
                        <th>{{ __('news.created_at') }}</th>
                        <th>{{ __('news.updated_at') }}</th>
                        <th width="7%"></th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($memberships as $item)
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" name="chkId" value="{{ $item->id }}">
                            </td>
                            <td class="text-center">{{ ++$index }}</td>

                            {{-- CODE --}}
                            <td>
                                <span class="label label-default">
                                    {{ $item->code }}
                                </span>
                            </td>

                            {{-- NAME --}}
                            <td>
                                <strong>{{ $item->name }}</strong>
                            </td>

                            {{-- MIN TOTAL SPENT --}}
                            <td class="text-right">
                                {{ number_format($item->min_total_spent) }}
                            </td>

                            {{-- PRIORITY --}}
                            <td class="text-center">
                                <span class="badge bg-blue">
                                    {{ $item->priority }}
                                </span>
                            </td>

                            {{-- ACTIVE --}}
                            <td class="text-center">
                                {!! $item->is_active ? '<i class="fa fa-check text-primary"></i>' : '' !!}
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
                                    @can('MembershipController@show')
                                        <a href="{{ route('memberships.show', $item->id) }}"
                                            class="btn btn-info btn-sm dropdown-item">
                                            <i class="fas fa-eye"></i> {{ __('message.view') }}
                                        </a>
                                    @endcan
                                    @can('MembershipController@update')
                                        <a href="{{ route('memberships.edit', $item->id) }}"
                                            class="btn btn-primary btn-sm dropdown-item">
                                            <i class="far fa-edit"></i> {{ __('message.edit') }}
                                        </a>
                                    @endcan

                                    @can('MembershipController@destroy')
                                        {!! Form::open([
                                            'method' => 'DELETE',
                                            'route' => ['memberships.destroy', $item->id],
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
                            <td colspan="9" class="text-center">
                                {{ __('Không có membership nào') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="box-footer clearfix">
            <div id="btn-act">
                @can('MembershipController@destroy')
                    <a href="#" id="deleteMemberships" data-action="deleteMemberships"
                        class="btn-act btn btn-danger btn-sm" title="{{ __('message.delete') }}">
                        <i class="fas fa-trash-alt" aria-hidden="true"></i>
                    </a>
                @endcan
                &nbsp;
                @can('MembershipController@active')
                    <a href="#" id="activeMemberships" data-action="activeMemberships"
                        class="btn-act btn btn-success btn-sm" title="{{ __('message.approved') }}">
                        <i class="fa fa-check" aria-hidden="true"></i>
                    </a>
                @endcan
            </div>
            <div class="page-footer pull-right">
                {!! $memberships->appends(\Request::except('page'))->render() !!}
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
                case 'activeMemberships':
                    actTxt = 'duyệt';
                    classAlert = 'alert-success';
                    break;
                case 'deleteMemberships':
                    actTxt = 'xóa';
                    classAlert = 'alert-danger';
                    break;
            }
            if (chkId.length != 0) {
                swal({
                        title: 'Bạn có muốn ' + actTxt +
                            ' hạng thành viên này không?',
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
                swal("Lỗi!", 'Vui lòng chọn hạng thành viên để  ' + actTxt + '!', "error")
            }
        }
    </script>
@endsection
