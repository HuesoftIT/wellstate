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

        {{-- BRANCH --}}
        <tr class="row {{ $errors->has('branch_id') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('branch_id', __('branch_room_types.branch'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('branch_id', $branches, old('branch_id', $branchRoomType->branch_id ?? null), [
                    'class' => 'form-control input-sm select2',
                    'placeholder' => __('branch_room_types.branch'),
                    'required',
                ]) !!}
                {!! $errors->first('branch_id', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- ROOM TYPE --}}
        <tr class="row {{ $errors->has('room_type_id') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('room_type_id', __('branch_room_types.room_type'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('room_type_id', $roomTypes, old('room_type_id', $branchRoomType->room_type_id ?? null), [
                    'class' => 'form-control input-sm select2',
                    'placeholder' => __('branch_room_types.room_type'),
                    'required',
                ]) !!}
                {!! $errors->first('room_type_id', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- CAPACITY --}}
        <tr class="row {{ $errors->has('capacity') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('capacity', __('room_types.capacity'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::number('capacity', old('capacity', $branchRoomType->capacity ?? null), [
                    'class' => 'form-control input-sm',
                    'required',
                    'min' => 1,
                ]) !!}
                {!! $errors->first('capacity', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- PRICE --}}
        <tr class="row {{ $errors->has('price') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('price_display', 'Giá phòng phụ thu (VND)', ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">

                <input type="text" id="price_display" class="form-control input-sm money-input"
                    value="{{ old('price', isset($branchRoomType) ? number_format($branchRoomType->price) : '') }}"
                    autocomplete="off">

                <input type="hidden" name="price" id="price"
                    value="{{ old('price', $branchRoomType->price ?? '') }}">

                {!! $errors->first('price', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- IS ACTIVE --}}
        <tr class="row {{ $errors->has('is_active') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('is_active', __('message.status'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::hidden('is_active', 0) !!}
                {!! Form::checkbox(
                    'is_active',
                    1,
                    old('is_active', isset($branchRoomType) ? (bool) $branchRoomType->is_active : true),
                    ['class' => 'flat-blue'],
                ) !!}
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
    <a href="{{ !empty($backUrl) ? $backUrl : url('admin/room-types') }}" class="btn btn-default"><i
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
