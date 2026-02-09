<div class="box-body">

    {{-- Hiển thị lỗi tổng --}}
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

        {{-- TITLE --}}
        <tr class="row {{ $errors->has('title') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('title', 'Tiêu đề bài viết', [
                    'class' => 'control-label label-required',
                ]) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('title', old('title', $post->title ?? null), [
                    'class' => 'form-control input-sm',
                    'required',
                ]) !!}
                {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- CATEGORY --}}
        <tr class="row {{ $errors->has('post_category_id') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('post_category_id', 'Danh mục', [
                    'class' => 'control-label label-required',
                ]) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select(
                    'post_category_id',
                    $categories->pluck('name', 'id'),
                    old('post_category_id', $post->post_category_id ?? null),
                    [
                        'class' => 'form-control select2',
                        'placeholder' => '-- Chọn danh mục --',
                        'required',
                    ],
                ) !!}
                {!! $errors->first('post_category_id', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- EXCERPT --}}
        <tr class="row {{ $errors->has('excerpt') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('excerpt', 'Mô tả ngắn', ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::textarea('excerpt', old('excerpt', $post->excerpt ?? null), [
                    'class' => 'form-control input-sm',
                    'rows' => 3,
                ]) !!}
                {!! $errors->first('excerpt', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- CONTENT --}}
        <tr class="row {{ $errors->has('content') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('content', 'Nội dung', [
                    'class' => 'control-label label-required',
                ]) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::textarea('content', old('content', $post->content ?? null), [
                    'class' => 'form-control editor',
                    'rows' => 8,
                    'required',
                ]) !!}
                {!! $errors->first('content', '<p class="help-block">:message</p>') !!}
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
                    <div class="imgprev-wrap" style="display:{{ !empty($post->image) ? 'block' : 'none' }}">
                        <input type="hidden" value="" name="img-hidden" />
                        <img class="img-preview"
                            src="{{ !empty($post->image) ? Storage::url($post->image) : '' }}"
                            alt="{{ trans('service.image') }}" />
                        <i class="fa fa-trash text-danger"></i>
                    </div>
                </div>
            </td>
        </tr>

        {{-- PUBLISHED AT --}}
        <tr class="row {{ $errors->has('published_at') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('published_at', 'Ngày đăng', ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::datetimeLocal(
                    'published_at',
                    old('published_at', isset($post->published_at) ? $post->published_at->format('Y-m-d\TH:i') : null),
                    ['class' => 'form-control input-sm'],
                ) !!}
                {!! $errors->first('published_at', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- IS ACTIVE --}}
        <tr class="row {{ $errors->has('is_active') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('is_active', 'Kích hoạt', ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::hidden('is_active', 0) !!}
                {!! Form::checkbox('is_active', 1, old('is_active', isset($post) ? (bool) $post->is_active : true), [
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
    <a href="{{ !empty($backUrl) ? $backUrl : url('admin/posts') }}" class="btn btn-default"><i
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
