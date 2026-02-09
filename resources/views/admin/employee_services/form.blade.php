<div class="box-body">

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p><i class="fa fa-fw fa-times"></i> {{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- back url --}}
    <input type="hidden" name="back_url" value="{{ $backUrl ?? old('back_url') }}">

    <table class="table table-condensed">

        {{-- EMPLOYEE --}}
        <tr class="{{ $errors->has('employee_id') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('employee_id', __('employees.employee'), [
                    'class' => 'control-label label-required',
                ]) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('employee_id', $employees->pluck('name', 'id'), old('employee_id', $employee->id), [
                    'class' => 'form-control input-sm select2',
                    'required',
                    'disabled',
                ]) !!}
                <input type="hidden" name="employee_id" value="{{ $employee->id }}">

                {!! $errors->first('employee_id', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <label class="control-label label-required">
                    {{ __('services.services') }}
                </label>

                <table class="table table-bordered mt-2">
                    @foreach ($serviceCategories as $category)
                        <tr class="bg-gray-light">
                            <td width="5%" class="text-center">
                                <input type="checkbox" class="chk-category" data-category="{{ $category->id }}">
                            </td>
                            <td colspan="2">
                                <strong>{{ $category->name }}</strong>
                            </td>
                        </tr>

                        @foreach ($category->services as $service)
                            <tr>
                                <td></td>
                                <td width="5%" class="text-center">
                                    <input type="checkbox" name="service_ids[]" value="{{ $service->id }}"
                                        class="chk-service" data-category="{{ $category->id }}"
                                        {{ in_array($service->id, old('service_ids', $selectedServiceIds ?? [])) ? 'checked' : '' }}>
                                </td>
                                <td>
                                    {{ $service->title }}
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </table>

                {!! $errors->first('service_ids', '<p class="help-block text-danger">:message</p>') !!}
            </td>
        </tr>


        {{-- STATUS --}}
        <tr>
            <td>
                {!! Form::label('is_active', __('message.status'), ['class' => 'control-label']) !!}
            </td>
            <td>
                {!! Form::hidden('is_active', 0) !!}
                {!! Form::checkbox('is_active', 1, old('is_active', $employeeService->is_active ?? true), [
                    'class' => 'flat-blue',
                ]) !!}
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
    <a href="{{ !empty($backUrl) ? $backUrl : url('admin/employees') }}" class="btn btn-default"><i
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
            $('#avatar').change(function() {
                var preview = document.querySelector('img.img-preview');
                var file = document.querySelector('#avatar').files[0];
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
                    document.querySelector('#avatar').value = '';
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
        document.addEventListener('DOMContentLoaded', function() {

            document.querySelectorAll('.chk-category').forEach(categoryCheckbox => {
                categoryCheckbox.addEventListener('change', function() {
                    let categoryId = this.dataset.category;
                    let checked = this.checked;

                    document.querySelectorAll('.chk-service[data-category="' + categoryId + '"]')
                        .forEach(service => service.checked = checked);
                });
            });

            document.querySelectorAll('.chk-service').forEach(serviceCheckbox => {
                serviceCheckbox.addEventListener('change', function() {
                    let categoryId = this.dataset.category;
                    let services = document.querySelectorAll('.chk-service[data-category="' +
                        categoryId + '"]');
                    let categoryCheckbox = document.querySelector('.chk-category[data-category="' +
                        categoryId + '"]');

                    categoryCheckbox.checked = [...services].every(s => s.checked);
                });
            });

        });
    </script>
@endsection
