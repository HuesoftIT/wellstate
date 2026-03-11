@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Promotion
@endsection

@section('contentheader_title')
    Promotion
@endsection

@section('breadcrumb')
    <ol class="breadcrumb">
        <li>
            <a href="{{ url('admin') }}">
                <i class="fa fa-home"></i> {{ __('message.dashboard') }}
            </a>
        </li>
        <li>
            <a href="{{ url('/admin/promotions') }}">
                Promotions
            </a>
        </li>
        <li class="active">{{ __('message.detail') }}</li>
    </ol>
@endsection

@section('main-content')
    <div class="box">
        <div class="box-header">
            <h5 class="float-left">{{ __('message.detail') }}</h5>
            <div class="box-tools">
                <a href="{{ url('/admin/promotions') }}" class="btn btn-default btn-sm mr-1">
                    <i class="fa fa-arrow-left"></i>
                    <span class="hidden-xs">{{ __('message.lists') }}</span>
                </a>

                @can('PromotionController@update')
                    <a href="{{ url('/admin/promotions/' . $promotion->id . '/edit') }}" class="btn btn-default btn-sm mr-1">
                        <i class="far fa-edit"></i>
                        <span class="hidden-xs">{{ __('message.edit') }}</span>
                    </a>
                @endcan

                @can('PromotionController@destroy')
                    {!! Form::open([
                        'method' => 'DELETE',
                        'url' => ['/admin/promotions', $promotion->id],
                        'style' => 'display:inline',
                    ]) !!}
                    {!! Form::button('<i class="far fa-trash-alt"></i><span class="hidden-xs">' . __('message.delete') . '</span>', [
                        'type' => 'submit',
                        'class' => 'btn btn-default btn-sm',
                        'onclick' => 'return confirm("' . __('message.confirm_delete') . '?")',
                    ]) !!}
                    {!! Form::close() !!}
                @endcan
            </div>
        </div>

        <div class="box-body table-responsive no-padding">
            <table class="table table-striped table-bordered">
                <tbody>

                    <tr>
                        <th width="25%">Tên khuyến mãi</th>
                        <td>
                            <strong>{{ $promotion->title }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <th>Mã khuyến mãi</th>
                        <td>
                            <code>{{ $promotion->discount_code ?? 'Không sử dụng mã' }}</code>
                        </td>
                    </tr>

                    <tr>
                        <th>Hình ảnh khuyến mãi</th>
                        <td>
                            @if ($promotion->image)
                                <img src="{{ asset('storage/' . $promotion->image) }}" class="img-responsive"
                                    style="max-height:250px;margin:auto;">
                            @else
                                <p class="text-muted">Không có hình ảnh</p>
                            @endif
                        </td>
                    </tr>


                    <tr>
                        <th>Phạm vi áp dụng</th>
                        <td>
                            @switch($promotion->apply_scope)
                                @case('booking')
                                    Toàn bộ booking
                                @break

                                @case('service')
                                    Dịch vụ
                                @break

                                @case('room')
                                    Phòng
                                @break
                            @endswitch
                        </td>
                    </tr>

                    <tr>
                        <th>Hình thức giảm</th>
                        <td>
                            @if ($promotion->discount_type === 'fixed')
                                <span class="label label-success">
                                    Giảm số tiền cố định
                                </span>
                            @else
                                <span class="label label-info">
                                    Giảm theo phần trăm (%)
                                </span>
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Giá trị giảm</th>
                        <td>
                            <strong class="text-danger">
                                {{ number_format($promotion->discount_value) }}
                                {{ $promotion->discount_type === 'percent' ? '%' : 'đ' }}
                            </strong>
                        </td>
                    </tr>

                    <tr>
                        <th>Giảm tối đa</th>
                        <td>
                            {{ $promotion->discount_max_value ? number_format($promotion->discount_max_value) . ' đ' : 'Không giới hạn' }}
                        </td>
                    </tr>

                    <tr>
                        <th>Đơn tối thiểu</th>
                        <td>
                            {{ $promotion->discount_min_order_value
                                ? number_format($promotion->discount_min_order_value) . ' đ'
                                : 'Không yêu cầu' }}
                        </td>
                    </tr>

                    <tr>
                        <th>Số lần sử dụng tối đa</th>
                        <td>
                            {{ $promotion->discount_max_uses ?? 'Không giới hạn' }}
                        </td>
                    </tr>

                    <tr>
                        <th>Số lần đã sử dụng</th>
                        <td>
                            <span class="badge bg-blue">
                                {{ $promotion->discount_uses_count }}
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <th>Số lần / mỗi khách</th>
                        <td>
                            {{ $promotion->discount_max_uses_per_user ?? 'Không giới hạn' }}
                        </td>
                    </tr>

                    <tr>
                        <th>Thời gian áp dụng</th>
                        <td>
                            <i class="fa fa-calendar"></i>

                            {{ $promotion->start_date->format(config('settings.format.date')) }}
                            →

                            {{ $promotion->end_date ? $promotion->end_date->format(config('settings.format.date')) : 'Không giới hạn' }}
                        </td>
                    </tr>

                    {{-- <tr>
                        <th>Tự động áp dụng</th>
                        <td>
                            @if ($promotion->is_auto)
                                <span class="label label-success">
                                    Tự động áp dụng
                                </span>
                            @else
                                <span class="label label-default">
                                    Cần nhập mã
                                </span>
                            @endif
                        </td>
                    </tr> --}}

                    <tr>
                        <th>Trạng thái</th>
                        <td>
                            @switch($promotion->status)
                                @case('active')
                                    <span class="label label-success">
                                        Đang áp dụng
                                    </span>
                                @break

                                @case('upcoming')
                                    <span class="label label-info">
                                        Sắp diễn ra
                                    </span>
                                @break

                                @case('expired')
                                    <span class="label label-default">
                                        Hết hạn
                                    </span>
                                @break

                                @case('disabled')
                                    <span class="label label-danger">
                                        Đã tắt
                                    </span>
                                @break
                            @endswitch
                        </td>
                    </tr>

                    <tr>
                        <th>Mô tả</th>
                        <td>
                            {{ $promotion->content ?: 'Không có mô tả' }}
                        </td>
                    </tr>

                    <tr>
                        <th>Ngày tạo</th>
                        <td>
                            {{ $promotion->created_at->format('d/m/Y H:i') }}
                        </td>
                    </tr>

                    <tr>
                        <th>Cập nhật lần cuối</th>
                        <td>
                            {{ $promotion->updated_at->format('d/m/Y H:i') }}
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

        {{-- Hiển thị điều kiện áp dụng --}}
        @if ($promotion->rules->count())
            <div class="box">
                <div class="box-header">
                    <h5>Điều kiện áp dụng</h5>
                </div>

                <div class="box-body">
                    <ul class="list-group">

                        @foreach ($promotion->rules as $rule)
                            <li class="list-group-item">

                                @switch($rule->type)
                                    {{-- SERVICE --}}
                                    @case('service')
                                        <strong>Dịch vụ:</strong>

                                        @if (($rule->config['mode'] ?? 'all') === 'all')
                                            <span class="label label-default">Tất cả dịch vụ</span>
                                        @else
                                            <div class="mt-1">
                                                @foreach ($services as $service)
                                                    <span class="label label-success">{{ $service->title }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                    @break

                                    {{-- MEMBERSHIP --}}
                                    @case('membership')
                                        <strong>Hạng thành viên:</strong>

                                        <div class="mt-1">
                                            @foreach ($memberships as $membership)
                                                <span class="label label-warning">{{ $membership->name }}</span>
                                            @endforeach
                                        </div>
                                    @break

                                    {{-- USER --}}
                                    @case('user')
                                        <strong>Khách hàng:</strong>

                                        @if (($rule->config['mode'] ?? 'all') === 'all')
                                            <span class="label label-default">Tất cả khách hàng</span>
                                        @else
                                            <div class="flex flex-column gap-2">
                                                @foreach ($users as $user)
                                                    <div class="">
                                                        <span class="label label-info">{{ $user->name }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    @break

                                    {{-- BIRTHDAY --}}
                                    @case('birthday')
                                        <strong>Sinh nhật:</strong>
                                        <span class="label label-danger">Khách sinh nhật 🎂</span>
                                    @break
                                @endswitch

                            </li>
                        @endforeach

                    </ul>
                </div>
            </div>
        @endif



    </div>
@endsection
