@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ trans('settings.setting_management') }}
@endsection

@section('main-content')
    <div id="alert"></div>
    <div class="box">
        <div class="content-header pb-5">
            <h5 class="float-left">
                {{ trans('settings.setting_management') }}
            </h5>
        </div>
        <ul class="nav nav-tabs">
            @for ($i = 0; $i < count($tabs); $i++)
                <li>
                    <a class="nav-link {{ $i == 0 ? 'active' : '' }}" href="#{{ $tabs[$i] }}"
                        aria-controls="{{ $tabs[$i] }}" role="tab" data-toggle="tab">
                        {{ trans('settings.' . $tabs[$i]) }}
                    </a>
                </li>
            @endfor
        </ul>
        {!! Form::open([
            'method' => 'PATCH',
            'url' => ['admin/settings'],
            'class' => 'form-horizontal',
            'files' => true,
            'id' => 'settings',
        ]) !!}
        <div class="tab-content">
            @for ($i = 0; $i < count($tabs); $i++)
                <div role="tabpanel" class="tab-pane {{ $i == 0 ? 'active' : '' }}" id="{{ $tabs[$i] }}">
                    <div class="box-body">
                        @foreach ($data[$tabs[$i]] as $item)
                            <div class="form-group {{ $errors->has('value') ? 'has-error' : '' }}">
                                {!! Form::label('description', $item['description'], ['class' => 'col-md-3 control-label']) !!}
                                <div class="col-md-6">
                                    @if ($item['type'] == 'image')
                                        <div class="image-upload-wrapper">

                                            {{-- Preview hiện tại --}}
                                            <div class="image-preview mb-2"
                                                style="display: {{ $item['value'] ? 'block' : 'none' }};">
                                                <img src="{{ $item['value'] ? asset($item['value']) : '' }}"
                                                    class="img-preview" style="max-height:100px">
                                            </div>

                                            {{-- Input file --}}
                                            <input type="file" name="{{ $item['key'] }}" class="image-input"
                                                accept="image/*" data-preview="{{ $item['key'] }}">

                                        </div>
                                    @elseif($item['type'] == 'number')
                                        {!! Form::number($item['key'], $item['value'], ['class' => 'form-control input-sm', 'id' => $item['key']]) !!}
                                    @elseif($item['type'] == 'checkbox')
                                        {!! Form::checkbox($item['key'], config('settings.active'), $item['value'], [
                                            'class' => 'flat-blue',
                                            'id' => $item['key'],
                                        ]) !!}
                                    @elseif($item['type'] == 'textarea')
                                        <textarea name={{ $item['key'] }} rows="5" class="form-control">{{ $item['value'] }}</textarea>
                                    @elseif($item['type'] == 'select')
                                        <select name="{{ $item['key'] }}" class="form-control input-sm">
                                            @foreach (config('theme.option_code') as $key => $val)
                                                <option value="{{ $key }}"
                                                    {{ $item['value'] === $key ? 'selected' : '' }}>{{ $val }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @else
                                        {!! Form::text($item['key'], $item['value'], ['class' => 'form-control input-sm', 'id' => $item['key']]) !!}
                                    @endif
                                    {!! $errors->first($item['key'], '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endfor
            <div class="box-footer">
                {!! Form::button('<i class="fa fa-check-circle"></i> ' . __('message.update'), [
                    'class' => 'btn btn-info',
                    'type' => 'submit',
                ]) !!}
            </div>
        </div>
        {!! Form::close() !!}
    </div>
@endsection
@section('scripts-footer')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            document.querySelectorAll('.image-input').forEach(function(input) {

                input.addEventListener('change', function() {

                    const file = this.files[0];
                    if (!file) return;

                    if (!file.type.startsWith('image/')) {
                        alert('File không hợp lệ');
                        this.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    const wrapper = this.closest('.image-upload-wrapper');
                    const previewBox = wrapper.querySelector('.image-preview');
                    const img = wrapper.querySelector('.img-preview');

                    reader.onload = function(e) {
                        img.src = e.target.result;
                        previewBox.style.display = 'block';
                    };

                    reader.readAsDataURL(file);
                });

            });

        });
    </script>
@endsection
