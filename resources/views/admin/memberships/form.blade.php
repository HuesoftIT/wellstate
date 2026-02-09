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

        {{-- CODE --}}
        <tr class="row {{ $errors->has('code') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('code', 'Mã hạng', [
                    'class' => 'control-label label-required',
                ]) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('code', old('code', $membership->code ?? null), [
                    'class' => 'form-control input-sm',
                    'required',
                    'placeholder' => 'bronze, silver, gold...',
                ]) !!}
                {!! $errors->first('code', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- NAME --}}
        <tr class="row {{ $errors->has('name') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('name', 'Tên hạng', [
                    'class' => 'control-label label-required',
                ]) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('name', old('name', $membership->name ?? null), [
                    'class' => 'form-control input-sm',
                    'required',
                    'placeholder' => 'Đồng, Bạc, Vàng...',
                ]) !!}
                {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- MIN TOTAL SPENT --}}

        <tr class="row {{ $errors->has('min_total_spent') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('min_total_spent_display', 'Giá (VND)', ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                <input type="text" id="min_total_spent_display" class="form-control input-sm money-input"
                    value="{{ old('min_total_spent', isset($membership) ? number_format($membership->min_total_spent) : '') }}"
                    autocomplete="off">

                <input type="hidden" name="min_total_spent" id="min_total_spent" value="{{ old('min_total_spent', $membership->min_total_spent ?? 0) }}">

                {!! $errors->first('min_total_spent', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- PRIORITY --}}
        <tr class="row {{ $errors->has('priority') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('priority', 'Độ ưu tiên', [
                    'class' => 'control-label label-required',
                ]) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::number('priority', old('priority', $membership->priority ?? 0), [
                    'class' => 'form-control input-sm',
                    'required',
                    'min' => 0,
                ]) !!}
                {!! $errors->first('priority', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- DESCRIPTION --}}
        <tr class="row {{ $errors->has('description') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('description', 'Mô tả', [
                    'class' => 'control-label',
                ]) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::textarea('description', old('description', $membership->description ?? null), [
                    'class' => 'form-control input-sm',
                    'rows' => 3,
                ]) !!}
                {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- IS ACTIVE --}}
        <tr class="row {{ $errors->has('is_active') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('is_active', 'Kích hoạt', [
                    'class' => 'control-label',
                ]) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::hidden('is_active', 0) !!}
                {!! Form::checkbox('is_active', 1, old('is_active', isset($membership) ? (bool) $membership->is_active : true), [
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
    <a href="{{ !empty($backUrl) ? $backUrl : url('admin/memberships') }}" class="btn btn-default"><i
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
