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


        {{-- NAME --}}
        <tr class="row {{ $errors->has('name') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('name', __('branches.name'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('name', old('name', $branch->name ?? null), [
                    'class' => 'form-control input-sm',
                    'required',
                ]) !!}
                {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
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
                    <div class="imgprev-wrap" style="display:{{ !empty($branch->image) ? 'block' : 'none' }}">
                        <input type="hidden" value="" name="img-hidden" />
                        <img class="img-preview"
                            src="{{ !empty($branch->image) ? Storage::url($branch->image) : '' }}"
                            alt="{{ trans('service.image') }}" />
                        <i class="fa fa-trash text-danger"></i>
                    </div>
                </div>
            </td>
        </tr>

        {{-- PHONE --}}
        <tr class="row {{ $errors->has('phone') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('phone', __('branches.phone'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('phone', old('phone', $branch->phone ?? null), [
                    'class' => 'form-control input-sm',
                ]) !!}
                {!! $errors->first('phone', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- ADDRESS --}}
        <tr class="row {{ $errors->has('address') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('address', __('branches.address'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('address', old('address', $branch->address ?? null), [
                    'class' => 'form-control input-sm',
                ]) !!}
                {!! $errors->first('address', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- OPEN TIME --}}
        <tr class="row {{ $errors->has('open_time') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('open_time', __('branches.open_time'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::time(
                    'open_time',
                    old('open_time', isset($branch->open_time) ? $branch->open_time->format('H:i') : null),
                    ['class' => 'form-control input-sm'],
                ) !!}
                {!! $errors->first('open_time', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- CLOSE TIME --}}
        <tr class="row {{ $errors->has('close_time') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('close_time', __('branches.close_time'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::time(
                    'close_time',
                    old('close_time', isset($branch->close_time) ? $branch->close_time->format('H:i') : null),
                    ['class' => 'form-control input-sm'],
                ) !!}
                {!! $errors->first('close_time', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- LATITUDE --}}
        <tr class="row {{ $errors->has('latitude') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('latitude', __('branches.latitude'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::number('latitude', old('latitude', $branch->latitude ?? null), [
                    'class' => 'form-control input-sm',
                    'step' => 'any',
                ]) !!}
                {!! $errors->first('latitude', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- LONGITUDE --}}
        <tr class="row {{ $errors->has('longitude') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('longitude', __('branches.longitude'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::number('longitude', old('longitude', $branch->longitude ?? null), [
                    'class' => 'form-control input-sm',
                    'step' => 'any',
                ]) !!}
                {!! $errors->first('longitude', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- NOTE --}}
        <tr class="row {{ $errors->has('note') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('note', __('branches.note'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::textarea('note', old('note', $branch->note ?? null), [
                    'class' => 'form-control input-sm',
                    'rows' => 3,
                ]) !!}
                {!! $errors->first('note', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- IS ACTIVE --}}
        <tr class="row {{ $errors->has('is_active') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('is_active', __('branches.active'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::hidden('is_active', 0) !!}
                {!! Form::checkbox('is_active', 1, old('is_active', isset($branch) ? (bool) $branch->is_active : true), [
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
    <a href="{{ !empty($backUrl) ? $backUrl : url('admin/branches') }}" class="btn btn-default"><i
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
@endsection
