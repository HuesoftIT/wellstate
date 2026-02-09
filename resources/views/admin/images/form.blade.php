<div class="box-body">

    {{-- Hiển thị lỗi --}}
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

        {{-- IMAGE CATEGORY --}}
        <tr class="row {{ $errors->has('image_category_id') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('image_category_id', __('images.category'), [
                    'class' => 'control-label label-required',
                ]) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('image_category_id', $categories, old('image_category_id', $image->image_category_id ?? null), [
                    'class' => 'form-control input-sm',
                    'placeholder' => __('images.choose'),
                    'required',
                ]) !!}
                {!! $errors->first('image_category_id', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- TITLE --}}
        <tr class="row {{ $errors->has('title') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('title', __('images.title'), [
                    'class' => 'control-label label-required',
                ]) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('title', old('title', $image->title ?? null), [
                    'class' => 'form-control input-sm',
                    'required',
                ]) !!}
                {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- LINK --}}
        <tr class="row {{ $errors->has('link') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('link', __('images.link'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('link', old('link', $image->link ?? null), [
                    'class' => 'form-control input-sm',
                    'placeholder' => 'https://...',
                ]) !!}
                {!! $errors->first('link', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- ORDER --}}
        <tr class="row {{ $errors->has('order') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('order', __('images.order'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::number('order', old('order', $image->order ?? 0), [
                    'class' => 'form-control input-sm',
                    'min' => 0,
                ]) !!}
                {!! $errors->first('order', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- IMAGE --}}
        <tr class="row {{ $errors->has('image') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('image', __('images.image'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                <div class="input-group inputfile-wrap">
                    <input type="text" class="form-control input-sm" readonly>
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-danger btn-sm">
                            <i class="fa fa-upload"></i> {{ __('message.upload') }}
                        </button>
                        {!! Form::file('image', [
                            'id' => 'image',
                            'class' => 'form-control input-sm',
                            'accept' => 'image/*',
                        ]) !!}
                    </div>
                </div>

                {!! $errors->first('image', '<p class="help-block">:message</p>') !!}

                <div class="imgprev-wrap mt-2" style="display:{{ !empty($image->image) ? 'block' : 'none' }}">
                    <img class="img-preview" src="{{ !empty($image->image) ? Storage::url($image->image) : '' }}"
                        style="max-height:120px">
                    <i class="fa fa-trash text-danger"></i>
                </div>
            </td>
        </tr>

        {{-- IS ACTIVE --}}
        <tr class="row {{ $errors->has('is_active') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('is_active', __('images.active'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::hidden('is_active', 0) !!}
                {!! Form::checkbox('is_active', 1, old('is_active', isset($image) ? (bool) $image->is_active : true), [
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
    <a href="{{ !empty($backUrl) ? $backUrl : url('admin/images') }}" class="btn btn-default"><i
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
