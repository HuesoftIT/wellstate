<div class="box-body">
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p><i class="fa fa-fw fa-check"></i> {{ $error }}</p>
            @endforeach
        </div>
    @endif
    <div>
        <input type="hidden" name="back_url" value="{{ !empty($backUrl) ? $backUrl : '' }}">
    </div>
    <table class="table table-condensed">

        {{-- NAME --}}
        <tr class="row {{ $errors->has('name') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('name', __('room_types.name'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('name', null, [
                    'class' => 'form-control input-sm',
                    'required' => 'required',
                ]) !!}
                {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- CAPACITY --}}
        <tr class="row {{ $errors->has('capacity') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('capacity', __('room_types.capacity'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::number('capacity', null, [
                    'class' => 'form-control input-sm',
                    'required' => 'required',
                    'min' => 1,
                ]) !!}
                {!! $errors->first('capacity', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- EXTRA FEE --}}

        <tr class="row {{ $errors->has('extra_fee') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('extra_fee_display', 'Giá (VND)', ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                <input type="text" id="extra_fee_display" class="form-control input-sm money-input"
                    value="{{ old('extra_fee', isset($roomType) ? number_format($roomType->extra_fee) : '') }}"
                    autocomplete="off">

                <input type="hidden" name="extra_fee" id="extra_fee"
                    value="{{ old('extra_fee', $roomType->extra_fee ?? '') }}">

                {!! $errors->first('extra_fee', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- IS ACTIVE --}}
         <tr class="row {{ $errors->has('is_active') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('is_active', __('branches.active'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::hidden('is_active', 0) !!}
                {!! Form::checkbox('is_active', 1, old('is_active', isset($roomType) ? (bool) $roomType->is_active : true), [
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
    <a href="{{ !empty($backUrl) ? $backUrl : url('admin/room-types') }}" class="btn btn-default"><i class="fas fa-times"></i>
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
