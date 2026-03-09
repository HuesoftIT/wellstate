<div class="box-body">
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p><i class="fa fa-fw fa-check"></i> {{ $error }}</p>
            @endforeach
        </div>
    @endif


    <table class="table table-condensed">

        <tr class="info">
            <td colspan="2">
                <strong>1. Thông tin khuyến mãi</strong>
            </td>
        </tr>

      
        {{-- Title --}}
        <tr class="row {{ $errors->has('title') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('title', 'Tên khuyến mãi', ['class' => 'control-label label-required']) !!}
            </td>

            <td class="col-md-8 col-lg-9">
                {!! Form::text('title', old('title', $promotion->title ?? null), [
                    'class' => 'form-control input-sm',
                    'required',
                ]) !!}

                {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- Code --}}
        <tr class="row {{ $errors->has('discount_code') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('discount_code', 'Mã khuyến mãi', ['class' => 'control-label']) !!}
            </td>

            <td class="col-md-8 col-lg-9">
                {!! Form::text('discount_code', old('discount_code', $promotion->discount_code ?? null), [
                    'class' => 'form-control input-sm',
                ]) !!}

                <small class="text-muted">Để trống nếu tự động áp dụng</small>
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
                            alt="{{ trans('service.image') }}" />
                        <i class="fa fa-trash text-danger"></i>
                    </div>
                </div>
            </td>
        </tr>

        {{-- Content --}}
        <tr class="row {{ $errors->has('content') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('content', 'Mô tả', ['class' => 'control-label']) !!}
            </td>

            <td class="col-md-8 col-lg-9">
                {!! Form::textarea('content', old('content', $promotion->content ?? null), [
                    'class' => 'form-control editor',
                    'rows' => 6,
                ]) !!}
            </td>
        </tr>

        <tr class="info">
            <td colspan="2">
                <strong>2. Thiết lập giảm giá</strong>
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
                        'percent' => 'Giảm theo %',
                        'fixed' => 'Giảm số tiền',
                    ],
                    old('discount_type', $promotion->discount_type ?? null),
                    ['class' => 'form-control input-sm', 'required'],
                ) !!}
            </td>
        </tr>

        {{-- Discount value --}}
        <tr class="row {{ $errors->has('discount_value') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('discount_value_display', 'Giá trị giảm', ['class' => 'control-label label-required']) !!}
            </td>

            <td class="col-md-8 col-lg-9">
                {{-- {!! Form::number('discount_value', old('discount_value', $promotion->discount_value ?? null), [
                    'class' => 'form-control input-sm',
                    'required',
                ]) !!} --}}

                <input type="text" id="discount_value_display" class="form-control input-sm money-input" required
                    value="{{ old('discount_value', isset($promotion) ? number_format($promotion->discount_value) : '') }}"
                    autocomplete="off">

                <input type="hidden" name="discount_value" id="discount_value"
                    value="{{ old('discount_value', isset($promotion) ? $promotion->discount_value : '') }}">
            </td>
        </tr>

        {{-- Max discount --}}
        <tr id="max_discount_row" class="row {{ $errors->has('discount_max_value') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('discount_max_value', 'Giảm tối đa', ['class' => 'control-label']) !!}
            </td>

            <td class="col-md-8 col-lg-9">
                <input type="text" id="discount_max_value_display" class="form-control input-sm money-input" required
                    value="{{ old('discount_max_value', isset($promotion) ? number_format($promotion->discount_max_value) : '') }}"
                    autocomplete="off">

                <input type="hidden" name="discount_max_value" id="discount_max_value"
                    value="{{ old('discount_max_value', isset($promotion) ? $promotion->discount_max_value : '') }}">
            </td>
        </tr>

        <tr class="info">
            <td colspan="2">
                <strong>3. Phạm vi áp dụng</strong>
            </td>
        </tr>

        <tr class="row">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('apply_scope', 'Áp dụng cho', ['class' => 'control-label']) !!}
            </td>

            <td class="col-md-8 col-lg-9">

                <label>
                    {!! Form::radio('apply_scope', 'booking', true) !!}
                    Toàn bộ booking
                </label>

                <br>

                <label>
                    {!! Form::radio('apply_scope', 'room') !!}
                    Chỉ tiền phòng
                </label>

                <br>

                <label>
                    {!! Form::radio('apply_scope', 'service') !!}
                    Chỉ dịch vụ
                </label>

            </td>
        </tr>

        <tr class="info">
            <td colspan="2">
                <strong>4. Điều kiện áp dụng</strong>
            </td>
        </tr>

        {{-- Min order --}}
        <tr class="row {{ $errors->has('discount_min_order_value') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('discount_min_order_value_display', 'Giá trị booking tối thiểu', ['class' => 'control-label']) !!}
            </td>

            <td class="col-md-8 col-lg-9">
                <input type="text" id="discount_min_order_value_display" class="form-control input-sm money-input" required
                    value="{{ old('discount_min_order_value', isset($promotion) ? number_format($promotion->discount_min_order_value) : '') }}"
                    autocomplete="off">

                <input type="hidden" name="discount_min_order_value" id="discount_min_order_value"
                    value="{{ old('discount_min_order_value', isset($promotion) ? $promotion->discount_min_order_value : '') }}">
            </td>
        </tr>

        {{-- Membership --}}
        <tr class="row">
            <td class="col-md-4 col-lg-3">
                <label class="control-label">Áp dụng cho membership</label>
            </td>

            <td class="col-md-8 col-lg-9">

                <select name="membership_levels[]" class="form-control select2" multiple>

                    @foreach ($memberships as $membership)
                        <option value="{{ $membership->id }}">
                            {{ $membership->name }}
                        </option>
                    @endforeach

                </select>

            </td>
        </tr>

        {{-- Birthday --}}
        <tr class="row">
            <td class="col-md-4 col-lg-3">
                <label class="control-label">Chỉ áp dụng sinh nhật</label>
            </td>

            <td class="col-md-8 col-lg-9">

                <label>
                    <input type="checkbox" name="rule_birthday" value="1">
                    Áp dụng cho khách sinh nhật
                </label>

            </td>
        </tr>

        <tr class="info">
            <td colspan="2">
                <strong>5. Giới hạn & thời gian</strong>
            </td>
        </tr>

        {{-- Max uses --}}
        <tr class="row {{ $errors->has('discount_max_uses') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('discount_max_uses', 'Tổng số lượt sử dụng', ['class' => 'control-label']) !!}
            </td>

            <td class="col-md-8 col-lg-9">
                {!! Form::number('discount_max_uses', old('discount_max_uses', $promotion->discount_max_uses ?? null), [
                    'class' => 'form-control input-sm',
                ]) !!}
            </td>
        </tr>

        {{-- Max uses per user --}}
        <tr class="row {{ $errors->has('discount_max_uses_per_user') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('discount_max_uses_per_user', 'Số lượt / mỗi khách', ['class' => 'control-label']) !!}
            </td>

            <td class="col-md-8 col-lg-9">
                {!! Form::number(
                    'discount_max_uses_per_user',
                    old('discount_max_uses_per_user', $promotion->discount_max_uses_per_user ?? null),
                    ['class' => 'form-control input-sm'],
                ) !!}
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

                const type = $('[name="discount_type"]').val()
                const row = $('#max_discount_row')
                const input = $('[name="discount_max_value"]')

                if (type === 'percent') {
                    row.show()
                    input.prop('disabled', false)
                } else {
                    row.hide()
                    input.prop('disabled', true)
                }
            }

            $('[name="discount_type"]').on('change', toggleMaxDiscount)

            toggleMaxDiscount()

        })
    </script>
@endsection
