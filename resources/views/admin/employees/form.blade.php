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

        {{-- BRANCH --}}
        <tr class="row {{ $errors->has('branch_id') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('branch_id', __('employees.branch'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('branch_id', $branches->pluck('name', 'id'), old('branch_id', $employee->branch_id ?? null), [
                    'class' => 'form-control input-sm',
                    'required',
                    'placeholder' => __('message.choose_branch'),
                ]) !!}
                {!! $errors->first('branch_id', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- CODE --}}
        <tr class="row {{ $errors->has('code') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('code', __('employees.code'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('code', old('code', $employee->code ?? null), [
                    'class' => 'form-control input-sm',
                    'placeholder' => 'Tự động nếu để trống',
                ]) !!}
                {!! $errors->first('code', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- NAME --}}
        <tr class="row {{ $errors->has('name') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('name', __('employees.name'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('name', old('name', $employee->name ?? null), [
                    'class' => 'form-control input-sm',
                    'required',
                ]) !!}
                {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- AVATAR --}}
        <tr class="row {{ $errors->has('avatar') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('avatar', __('employees.avatar'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                <div class="input-group inputfile-wrap">
                    <input type="text" class="form-control input-sm" readonly>
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-danger btn-sm">
                            <i class="fa fa-upload"></i> {{ __('message.upload') }}
                        </button>
                        {!! Form::file(
                            'avatar',
                            array_merge(['id' => 'avatar', 'class' => 'form-control input-sm', 'accept' => 'image/*']),
                        ) !!}
                    </div>
                    {!! $errors->first('avatar', '<p class="help-block">:message</p>') !!}
                </div>
                <div class="clearfix"></div>
                <div class="imgprev-wrap" style="display:{{ !empty($employee->avatar) ? 'block' : 'none' }}">
                    <input type="hidden" value="" name="img-hidden" />
                    <img class="img-preview" src="{{ !empty($employee->avatar) ? Storage::url($employee->avatar) : '' }}"
                        alt="{{ trans('service.image') }}" />
                    <i class="fa fa-trash text-danger"></i>
                </div>

            </td>
        </tr>

        {{-- PHONE --}}
        <tr class="row {{ $errors->has('phone') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('phone', __('employees.phone'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('phone', old('phone', $employee->phone ?? null), [
                    'class' => 'form-control input-sm',
                ]) !!}
                {!! $errors->first('phone', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- IS ACTIVE --}}
        <tr class="row {{ $errors->has('is_active') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('is_active', __('employees.active'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::hidden('is_active', 0) !!}
                {!! Form::checkbox('is_active', 1, old('is_active', isset($employee) ? (bool) $employee->is_active : true), [
                    'class' => 'flat-blue',
                ]) !!}
                {!! $errors->first('is_active', '<p class="help-block">:message</p>') !!}
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
@endsection
