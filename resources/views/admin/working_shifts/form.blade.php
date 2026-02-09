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
                {!! Form::select(
                    'branch_id',
                    $branches->pluck('name', 'id'),
                    old('branch_id', $workingShift->branch_id ?? null),
                    [
                        'class' => 'form-control input-sm',
                        'required',
                        'placeholder' => __('message.choose_branch'),
                    ],
                ) !!}
                {!! $errors->first('branch_id', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- NAME --}}
        <tr class="row {{ $errors->has('name') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('name', __('working_shifts.example_name'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('name', old('name', $workingShift->name ?? null), [
                    'class' => 'form-control input-sm',
                    'required',
                    'placeholder' => __('working_shifts.example_name'), // Ca sáng / Ca chiều
                ]) !!}
                {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- START TIME --}}
        <tr class="row {{ $errors->has('start_time') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('start_time', __('working_shifts.start_time'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::time(
                    'start_time',
                    old(
                        'start_time',
                        isset($workingShift->start_time) ? \Carbon\Carbon::parse($workingShift->start_time)->format('H:i') : null,
                    ),
                    [
                        'class' => 'form-control input-sm',
                        'required',
                    ],
                ) !!}

                {!! $errors->first('start_time', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        {{-- END TIME --}}
        <tr class="row {{ $errors->has('end_time') ? 'has-error' : '' }}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('end_time', __('working_shifts.end_time'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::time(
                    'end_time',
                    old(
                        'end_time',
                        isset($workingShift->end_time) ? \Carbon\Carbon::parse($workingShift->end_time)->format('H:i') : null,
                    ),
                    [
                        'class' => 'form-control input-sm',
                        'required',
                    ],
                ) !!}
                {!! $errors->first('end_time', '<p class="help-block">:message</p>') !!}
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
                    old('is_active', isset($workingShift) ? (bool) $workingShift->is_active : true),
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
    <a href="{{ !empty($backUrl) ? $backUrl : url('admin/working-shifts') }}" class="btn btn-default"><i
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
