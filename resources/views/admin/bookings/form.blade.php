<div class="box-body">
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p><i class="fa fa-fw fa-check"></i> {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <table class="table table-condensed">

        {{-- Danh mục dịch vụ --}}
        <tr class="row {{ $errors->has('service_category_id') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('service_category_id', 'Danh mục dịch vụ', ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('service_category_id', $categories, null, [
                    'class' => 'form-control input-sm',
                    'required',
                    'placeholder' => '-- Chọn danh mục --',
                ]) !!}
                {!! $errors->first('service_category_id', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- Tên dịch vụ --}}
        <tr class="row {{ $errors->has('name') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('name', 'Tên dịch vụ', ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('name', null, ['class' => 'form-control input-sm', 'required']) !!}
                {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- Combo --}}
        <tr class="row">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('is_combo', 'Là combo?', ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::checkbox('is_combo', 1, isset($service) ? $service->is_combo : false, ['class' => 'flat-blue']) !!}
            </td>
        </tr>

        {{-- Chọn dịch vụ cho combo --}}
        <tr class="row combo-section" style="display:none">
            <td class="col-md-4 col-lg-3">
                <label class="control-label label-required">Dịch vụ trong combo</label>
            </td>
            <td class="col-md-8 col-lg-9">
                <table class="table table-bordered" id="combo-services-table">
                    <thead>
                        <tr>
                            <th>Dịch vụ</th>
                            <th width="100">Số lượng</th>
                            <th width="120">Thời gian (phút)</th>
                            <th width="150">Giá</th>
                            <th width="50"></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

                <button type="button" class="btn btn-sm btn-primary" id="add-combo-service">
                    <i class="fa fa-plus"></i> Thêm dịch vụ
                </button>
            </td>
        </tr>



        {{-- Hình ảnh --}}
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
                    <div class="imgprev-wrap" style="display:{{ !empty($service->image) ? 'block' : 'none' }}">
                        <input type="hidden" value="" name="img-hidden" />
                        <img class="img-preview"
                            src="{{ !empty($service->image) ? Storage::url($service->image) : '' }}"
                            alt="{{ trans('service.image') }}" />
                        <i class="fa fa-trash text-danger"></i>
                    </div>
                </div>
            </td>
        </tr>

        {{-- Slug --}}
        {{-- <tr class="row {{ $errors->has('slug') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('slug', 'Slug', ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('slug', null, ['class' => 'form-control input-sm']) !!}
                <small class="text-muted">Để trống sẽ tự sinh theo tên</small>
                {!! $errors->first('slug', '<p class="help-block">:message</p>') !!}
            </td>
        </tr> --}}

        {{-- Thời gian --}}
        <tr class="row {{ $errors->has('duration') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('duration', 'Thời gian (phút)', ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::number('duration', null, ['class' => 'form-control input-sm', 'required', 'min' => 1]) !!}
                {!! $errors->first('duration', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- Giá --}}
        <tr class="row {{ $errors->has('price') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('price_display', 'Giá (VND)', ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                <input type="text" id="price_display" class="form-control input-sm money-input"
                    value="{{ old('price', isset($service) ? number_format($service->price) : '') }}"
                    autocomplete="off">

                <input type="hidden" name="price" id="price">

                {!! $errors->first('price', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>


        {{-- Giá khuyến mãi --}}
        <tr class="row {{ $errors->has('sale_price') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('sale_price_display', 'Giá khuyến mãi (VND)', ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                <input type="text" id="sale_price_display" class="form-control input-sm money-input"
                    value="{{ old('sale_price', isset($service) && $service->sale_price ? number_format($service->sale_price) : '') }}"
                    autocomplete="off">

                <input type="hidden" name="sale_price" id="sale_price">

                {!! $errors->first('sale_price', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>




        {{-- Mô tả --}}
        <tr class="row {{ $errors->has('description') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('description', 'Mô tả', ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::textarea('description', null, ['class' => 'form-control input-sm', 'rows' => 5]) !!}
                {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- Kích hoạt --}}
        <tr class="row">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('is_active', 'Kích hoạt', ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::checkbox('is_active', 1, isset($service) ? $service->is_active : true, ['class' => 'flat-blue']) !!}
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
        CKEDITOR.replace('content', {
            filebrowserBrowseUrl: '{{ route('ckfinder_browser') }}',
        });
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
        window.servicesData = @json($servicesForCombo);
    </script>
    <script>
        window.existingComboItems = @json(isset($service) && $service->is_combo
                ? $service->comboItems->map(fn($item) => [
                        'service_id' => $item->service_id,
                        'quantity' => $item->quantity,
                    ]
        )
                : []
        );
    </script>

    <script>
        $(function() {

            function toggleCombo() {
                if ($('[name="is_combo"]').is(':checked')) {
                    console.log('123e')
                    $('.combo-section').show();
                } else {
                    $('.combo-section').hide();
                }
            }
            toggleCombo();
            $('[name="is_combo"]').on('ifChanged', function() {
                toggleCombo();
            });

            function serviceOptions(excludeIds = []) {
                return servicesData
                    .filter(s => !excludeIds.includes(String(s.id)))
                    .map(s => `
            <option value="${s.id}" data-price="${s.price}" data-duration="${s.duration}">
                ${s.name}
            </option>
        `)
                    .join('');
            }

            $('#add-combo-service').click(function() {
                const index = Date.now();

                const row = $(`
                    <tr>
                        <td>
                            <select name="combo_items[${index}][service_id]"
                                    class="form-control select2 service-select"
                                    style="width:100%">
                                <option value="">-- Chọn dịch vụ --</option>
                                ${serviceOptions()}
                            </select>
                        </td>
                        <td>
                            <input type="number"
                                name="combo_items[${index}][quantity]"
                                class="form-control qty"
                                value="1" min="1">
                        </td>
                        <td class="duration">0</td>
                        <td class="price">0</td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm remove-row">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    `);

                $('#combo-services-table tbody').append(row);

                row.find('.select2').select2({
                    placeholder: 'Chọn dịch vụ',
                    allowClear: true
                });

                updateServiceOptions();
            });


            function recalcCombo() {
                let totalPrice = 0;
                let totalDuration = 0;

                $('#combo-services-table tbody tr').each(function() {
                    const select = $(this).find('.service-select option:selected');
                    const qty = parseInt($(this).find('.qty').val()) || 1;

                    const price = parseFloat(select.data('price') || 0) * qty;
                    const duration = parseInt(select.data('duration') || 0) * qty;

                    $(this).find('.price').text(price.toLocaleString());
                    $(this).find('.duration').text(duration);

                    totalPrice += price;
                    totalDuration += duration;
                });

                $('#price_display').val(totalPrice.toLocaleString());
                $('#price').val(totalPrice);

                $('#duration').val(totalDuration);
            }

            function updateServiceOptions() {
                let selectedIds = [];

                $('.service-select').each(function() {
                    if ($(this).val()) {
                        selectedIds.push($(this).val());
                    }
                });

                $('.service-select').each(function() {
                    let select = $(this);
                    let currentValue = select.val();

                    let html = `
            <option value="">-- Chọn dịch vụ --</option>
            ${serviceOptions(selectedIds.filter(id => id !== currentValue))}
        `;

                    select
                        .html(html)
                        .val(currentValue)
                        .select2({
                            placeholder: 'Chọn dịch vụ',
                            allowClear: true,
                            width: '100%'
                        });
                });
            }

            function addComboRow(serviceId = null, quantity = 1) {
                const index = Date.now() + Math.random();

                const row = $(`
                    <tr>
                        <td>
                            <select name="combo_items[${index}][service_id]"
                                    class="form-control select2 service-select"
                                    style="width:100%">
                                <option value="">-- Chọn dịch vụ --</option>
                            </select>
                        </td>
                        <td>
                            <input type="number"
                                name="combo_items[${index}][quantity]"
                                class="form-control qty"
                                value="${quantity}" min="1">
                        </td>
                        <td class="duration">0</td>
                        <td class="price">0</td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm remove-row">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `);

                $('#combo-services-table tbody').append(row);

                const select = row.find('.service-select');

                select.html(`
        <option value="">-- Chọn dịch vụ --</option>
        ${serviceOptions()}
    `);

                select.val(serviceId).select2({
                    placeholder: 'Chọn dịch vụ',
                    allowClear: true,
                    width: '100%'
                });

                updateServiceOptions();
                recalcCombo();
            }

            function toggleCombo() {
                if ($('[name="is_combo"]').is(':checked')) {
                    $('.combo-section').show();
                } else {
                    $('.combo-section').hide();
                    $('#combo-services-table tbody').empty(); 
                }
            }



            $(document).on('change keyup', '.service-select, .qty', recalcCombo);
            $(document).on('change', '.service-select', function() {
                recalcCombo();
                updateServiceOptions();
            });

            $(document).on('click', '.remove-row', function() {
                $(this).closest('tr').remove();
                recalcCombo();
                updateServiceOptions();
            });
            if (typeof existingComboItems !== 'undefined' && existingComboItems.length) {
                $('.combo-section').show();

                existingComboItems.forEach(item => {
                    addComboRow(item.service_id, item.quantity);
                });
            }

        });
    </script>
@endsection
