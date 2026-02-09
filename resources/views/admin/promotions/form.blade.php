<div class="box-body">
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p><i class="fa fa-fw fa-check"></i> {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <table class="table table-condensed">

        {{-- Type --}}
        <tr class="row {{ $errors->has('type') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('type', 'Loại khuyến mãi', ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select(
                    'type',
                    [
                        'promotion' => __('promotions.types.promotion'),
                        'membership' => __('promotions.types.membership'),
                    ],
                    null,
                    ['class' => 'form-control input-sm', 'required', 'placeholder' => '-- Chọn loại --'],
                ) !!}
                {!! $errors->first('type', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- Title --}}
        <tr class="row {{ $errors->has('title') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('title', 'Tên khuyến mãi', ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('title', null, ['class' => 'form-control input-sm', 'required']) !!}
                {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- Code --}}
        <tr class="row {{ $errors->has('discount_code') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('discount_code', 'Code khuyến mãi', ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('discount_code', null, ['class' => 'form-control input-sm']) !!}

                <small class="text-muted">
                    Để trống hệ thống sẽ tự động tạo mã khuyến mãi
                </small>

                {!! $errors->first('discount_code', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>


        {{-- Image --}}
        <tr class="row {{ $errors->has('image') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('image', trans('news.image'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                <div>
                    <div class="input-group inputfile-wrap ">
                        <input type="text" class="form-control input-sm" readonly>
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-danger btn-sm">
                                <i class=" fa fa-upload"></i>
                                {{ __('message.upload') }}
                            </button>
                            {!! Form::file(
                                'image',
                                array_merge(['id' => 'image', 'class' => 'form-control input-sm', 'accept' => 'image/*']),
                            ) !!}
                        </div>
                        {!! $errors->first('image', '<p class="help-block">:message</p>') !!}
                    </div>
                    <div class="clearfix"></div>
                    <div class="imgprev-wrap" style="display:{{ !empty($promotion->image) ? 'block' : 'none' }}">
                        <input type="hidden" value="" name="img-hidden" />
                        <img class="img-preview"
                            src="{{ !empty($promotion->image) ? Storage::url($promotion->image) : '' }}"
                            alt="{{ trans('promotion.image') }}" />
                        <i class="fa fa-trash text-danger"></i>
                    </div>
                </div>
            </td>
        </tr>

        {{-- content --}}
        <tr class="row {{ $errors->has('content') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('content', 'Nội dung', [
                    'class' => 'control-label label-required',
                ]) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::textarea('content', old('content', $promotion->content ?? null), [
                    'class' => 'form-control editor',
                    'rows' => 8,
                    'required',
                ]) !!}
                {!! $errors->first('content', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- Discount type --}}
        <tr class="row {{ $errors->has('discount_type') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('discount_type', 'Loại giảm giá', ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select(
                    'discount_type',
                    [
                        'percent' => __('promotions.discount_types.percent'),
                        'fixed' => __('promotions.discount_types.fixed'),
                    ],
                    null,
                    ['class' => 'form-control input-sm', 'required', 'placeholder' => '-- Chọn loại --'],
                ) !!}
                {!! $errors->first('discount_type', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- Discount value --}}
        <tr class="row {{ $errors->has('discount_value') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('discount_value', 'Giá trị giảm', ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">

                <input type="text" id="discount_value_display" class="form-control input-sm money-input"
                    value="{{ old('discount_value', isset($promotion) ? number_format($promotion->discount_value) : '') }}"
                    autocomplete="off">

                <input type="hidden" name="discount_value" id="discount_value"
                    value="{{ old('discount_value', isset($promotion) ? $promotion->discount_value : '') }}">

                {!! $errors->first('discount_value', '<p class="help-block">:message</p>') !!}

            </td>
        </tr>

        {{-- Discount min order value --}}
        <tr class="row {{ $errors->has('discount_min_order_value') ? 'has-error' : '' }}"
            id="discount_min_order_value_row">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('discount_min_order_value', 'Giá trị order tối thiểu', ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                <input type="text" id="discount_min_order_value_display" class="form-control input-sm money-input"
                    value="{{ old('discount_min_order_value', isset($promotion) ? number_format($promotion->discount_min_order_value) : '') }}"
                    autocomplete="off">

                <input type="hidden" name="discount_min_order_value" id="discount_min_order_value"
                    value="{{ old('discount_min_order_value', isset($promotion) ? $promotion->discount_min_order_value : '') }}">

                {!! $errors->first('discount_min_order_value', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- Max discount --}}
        <tr class="row {{ $errors->has('discount_max_value') ? 'has-error' : '' }}" id="discount_max_value_row">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('discount_max_value', 'Giảm tối đa', ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                <input type="text" id="discount_max_value_display" class="form-control input-sm money-input"
                    value="{{ old('discount_max_value', isset($promotion) ? number_format($promotion->discount_max_value) : '') }}"
                    autocomplete="off">

                <input type="hidden" name="discount_max_value" id="discount_max_value"
                    value="{{ old('discount_max_value', isset($promotion) ? $promotion->discount_max_value : '') }}">

                {!! $errors->first('discount_max_value', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- Discount max uses --}}
        <tr class="row {{ $errors->has('discount_max_uses') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('discount_max_uses', 'Tổng số lượt sử dụng', ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::number('discount_max_uses', null, [
                    'class' => 'form-control input-sm',
                    'min' => 1,
                    'placeholder' => 'Ví dụ: 100',
                    'required',
                ]) !!}
                {!! $errors->first('discount_max_uses', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>


        {{-- Discount max uses per user --}}
        <tr class="row {{ $errors->has('discount_max_uses_per_user') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('discount_max_uses_per_user', 'Số lượt tối đa / mỗi khách hàng', [
                    'class' => 'control-label label-required',
                ]) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::number('discount_max_uses_per_user', null, [
                    'class' => 'form-control input-sm',
                    'min' => 1,
                    'placeholder' => 'Ví dụ: 1',
                    'required',
                ]) !!}
                {!! $errors->first('discount_max_uses_per_user', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>



        {{-- Start date --}}
        <tr class="row {{ $errors->has('start_date') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('start_date', 'Ngày bắt đầu', ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::date('start_date', isset($promotion->start_date) ? $promotion->start_date->format('Y-m-d') : null, [
                    'class' => 'form-control input-sm',
                    'required',
                ]) !!}
                {!! $errors->first('start_date', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- End date --}}
        <tr class="row {{ $errors->has('end_date') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('end_date', 'Ngày kết thúc', ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::date('end_date', isset($promotion->end_date) ? $promotion->end_date->format('Y-m-d') : null, [
                    'class' => 'form-control input-sm',
                ]) !!}
                {!! $errors->first('end_date', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>


        @php
            $serviceRule = $rules['service'] ?? null;
            $serviceMode = $serviceRule->config['mode'] ?? 'all';
            $serviceIds = $serviceRule->config['ids'] ?? [];
        @endphp

        {{-- Điều kiện dịch vụ --}}
        <tr class="row">
            <td class="col-md-4 col-lg-3">
                <label class="control-label label-required">Áp dụng cho dịch vụ</label>
            </td>
            <td class="col-md-8 col-lg-9">

                <label>
                    <input type="radio" name="service_rule" value="all"
                        {{ $serviceMode === 'all' ? 'checked' : '' }}>
                    Toàn bộ dịch vụ
                </label>

                <br>

                <label>
                    <input type="radio" name="service_rule" value="only"
                        {{ $serviceMode === 'only' ? 'checked' : '' }}>
                    Chỉ dịch vụ được chọn
                </label>

                <div id="service_rule_box" class="mt-2 {{ $serviceMode === 'only' ? '' : 'd-none' }}">
                    <select name="service_ids[]" class="form-control select2" multiple>
                        @foreach ($services as $id => $name)
                            <option value="{{ $id }}" {{ in_array($id, $serviceIds) ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>


            </td>
        </tr>


        @php
            $membershipRule = $rules['membership'] ?? null;
            $membershipIds = $membershipRule->config['ids'] ?? [];
            $membershipAll = empty($membershipIds);
        @endphp

        {{-- Điều kiện thành viên --}}
        {{-- @include('admin.promotions.rules.membership') --}}
        <tr class="row">
            <td class="col-md-4 col-lg-3">
                <label class="control-label label-required">Áp dụng cho thành viên</label>
            </td>
            <td class="col-md-8 col-lg-9">

                <label>
                    <input type="checkbox" name="membership_all" value="1"
                        {{ $membershipAll ? 'checked' : '' }}>
                    Toàn bộ thành viên
                </label>

                <div id="membership_box" class="mt-2 {{ $membershipAll ? 'd-none' : '' }}">
                    <select name="membership_levels[]" class="form-control select2" multiple>
                        @foreach ($memberships as $membership)
                            <option value="{{ $membership->id }}"
                                {{ in_array($membership->id, $membershipIds) ? 'selected' : '' }}>
                                {{ $membership->name }}
                            </option>
                        @endforeach
                    </select>
                </div>


            </td>
        </tr>



        @php
            $birthdayEnabled = isset($rules['birthday']);
        @endphp


        {{-- @include('admin.promotions.rules.birthday') --}}
        <tr class="row">
            <td class="col-md-4 col-lg-3">
                <label class="control-label">Chỉ áp dụng sinh nhật</label>
            </td>
            <td class="col-md-8 col-lg-9">
                <label>
                    <input type="checkbox" name="rule_birthday" value="1"
                        {{ $birthdayEnabled ? 'checked' : '' }}>Áp dụng cho khách sinh nhật
                </label>
            </td>
        </tr>


        @php
            $userRule = $rules['user'] ?? null;
            $userMode = $userRule->config['mode'] ?? 'all';
            $userIds = $userRule->config['ids'] ?? [];
        @endphp
        {{-- @include('admin.promotions.rules.user') --}}
        <tr class="row">
            <td class="col-md-4 col-lg-3">
                <label class="control-label">Giới hạn khách hàng</label>
            </td>
            <td class="col-md-8 col-lg-9">

                <label>
                    <input type="radio" name="user_rule" value="all"
                        {{ $userMode === 'all' ? 'checked' : '' }}>Áp dụng cho tất cả khách hàng
                </label>

                <br>

                <label>
                    <input type="radio" name="user_rule" value="only"
                        {{ $userMode === 'only' ? 'checked' : '' }}>
                    Chỉ áp dụng cho khách hàng được chọn
                </label>

                <div id="user_box" class="mt-2 {{ $userMode === 'only' ? '' : 'd-none' }}">
                    <select name="user_ids[]" class="form-control select2 select2-users" multiple>
                        @foreach ($userIds as $userId)
                            <option value="{{ $userId }}" selected>
                                {{ $customers[$userId]->name . ' - ' . $customers[$userId]->phone ?? 'Khách #' . $userId }}
                            </option>
                        @endforeach
                    </select>
                </div>




            </td>
        </tr>




        {{-- Active --}}
        <tr class="row">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('is_active', 'Kích hoạt', ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::checkbox('is_active', 1, isset($promotion) ? $promotion->is_active : true, ['class' => 'flat-blue']) !!}
            </td>
        </tr>
    </table>

</div>



<div class="box-footer">
    {!! Form::button(
        '<i class="fa fa-check-circle"></i> ' . ($text = isset($submitButtonText) ? $submitButtonText : __('message.save')),
        [
            'class' => 'btn btn-info mr-2',
            'type' => 'submit',
        ],
    ) !!}
    <a href="{{ !empty($backUrl) ? $backUrl : url('admin/services') }}" class="btn btn-default"><i
            class="fas fa-times"></i>
        {{ __('message.close') }}</a>
</div>

@section('scripts-footer')
    <script type="text/javascript" src="{{ asset('plugins/ckeditor_full/ckeditor.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/ckfinder/ckfinder.js') }}"></script>
    <script>
        CKFinder.config({
            connectorPath: '/ckfinder/connector'
        });
    </script>
    <script>
        // CKEDITOR.replace('content', {
        //     filebrowserBrowseUrl: '{{ route('ckfinder_browser') }}',
        // });
    </script>
    @include('ckfinder::setup')
    <script type="text/javascript">
        $(function() {
            $('#image').change(function() {
                var preview = document.querySelector('img.img-preview');
                var file = document.querySelector('#image').files[0];
                var reader = new FileReader();

                if (/\.(jpe?g|png|gif)$/i.test(file.name)) {

                    reader.addEventListener("load", function() {
                        preview.src = reader.result;
                        $('.imgprev-wrap').css('display', 'block');
                        $('.inputfile-wrap').find('input[type=text]').val(file.name);
                    }, false);

                    if (file) {
                        reader.readAsDataURL(file);
                    }
                } else {
                    document.querySelector('#image').value = '';
                    $('.imgprev-wrap').find('input[type=hidden]').val('');
                }
            });

            $('.imgprev-wrap .fa-trash').click(function() {
                var preview = document.querySelector('img.img-preview');

                if (confirm('{{ __('message.confirm_delete') }}')) {
                    preview.src = '';
                    $('.imgprev-wrap').css('display', 'none');
                    $('.inputfile-wrap').find('input[type=text]').val('');
                }
            })
        });
    </script>

    <script>
        $(function() {

            $('.select2').select2();

            $('input[name="service_rule"]').on('change', function() {
                if ($(this).val() === 'only') {
                    $('#service_rule_box').removeClass('d-none');
                } else {
                    $('#service_rule_box').addClass('d-none');
                    $('select[name="service_ids[]"]').val(null).trigger('change');
                }
            });

            $('input[name="membership_all"]').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#membership_box').addClass('d-none');
                    $('select[name="membership_levels[]"]').val(null).trigger('change');
                } else {
                    $('#membership_box').removeClass('d-none');
                }
            });

            $('input[name="user_rule"]').on('change', function() {
                if (this.value === 'only') {
                    $('#user_box').removeClass('d-none');
                } else {
                    $('#user_box').addClass('d-none');
                    $('#user_box select').val(null).trigger('change');
                }
            });


            const $userSelect = $('.select2-users');

            $userSelect.select2({
                ajax: {
                    url: '/api/customers/search',
                    dataType: 'json',
                    delay: 300,
                    data: function(params) {
                        return {
                            q: params.term,
                            membership_levels: $('select[name="membership_levels[]"]').val()
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.map(user => ({
                                id: user.id,
                                text: `${user.name} - ${user.phone}`
                            }))
                        };
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    },
                    language: {
                        errorLoading: function() {
                            return 'Không thể tải dữ liệu';
                        },
                        inputTooShort: function() {
                            return 'Vui lòng nhập ít nhất 1 ký tự';
                        },
                        loadingMore: function() {
                            return 'Đang tải thêm kết quả…';
                        },
                        noResults: function() {
                            return 'Không tìm thấy kết quả phù hợp';
                        },
                        searching: function() {
                            return 'Đang tìm kiếm…';
                        }
                    }
                },
                width: '100%'
            });

        });
    </script>
    <script>
        $(function() {

            function toggleMaxDiscount() {
                let type = $('select[name="discount_type"]').val();

                if (type === 'percent') {
                    $('#discount_max_value_row').removeClass('d-none');
                } else {
                    $('#discount_max_value_row').addClass('d-none');

                    $('#discount_max_value_display').val('');
                    $('#discount_max_value').val('');
                }
            }

            toggleMaxDiscount();

            $('select[name="discount_type"]').on('change', function() {
                toggleMaxDiscount();
            });

        });
    </script>
@endsection
