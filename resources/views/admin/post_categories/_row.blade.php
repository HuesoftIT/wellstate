<tr>
    <td class="text-center">
        <input type="checkbox" name="chkId" value="{{ $item->id }}">
    </td>

    <td class="text-center">{{ ++$index }}</td>

    <td>
        {{-- thụt vào theo cấp --}}
        <span style="padding-left: {{ $level * 20 }}px">
            @if ($level > 0)
                └─
            @endif
        </span>

        <a href="{{ route('post-categories.show', $item->id) }}" class="text-black">
            {{ $item->name }}
        </a>
    </td>

    <td>{{ $item->description }}</td>

    <td class="text-center">{{ $item->order }}</td>

    <td class="text-center">
        @if ($item->is_active)
            <i class="fa fa-check text-primary"></i>
        @endif
    </td>

    <td class="text-center">
        {{ optional($item->created_at)->format(config('settings.format.date')) }}
    </td>

    <td class="text-center">
        {{ optional($item->updated_at)->format(config('settings.format.date')) }}
    </td>


    {{-- ACTION --}}
    <td class="dropdown">
        <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            <i class="fal fa-tools"></i>
        </button>

        <div class="dropdown-menu p-0">
            @can('PostCategoryController@show')
                <a href="{{ route('post-categories.show', $item->id) }}" class="btn btn-info btn-sm dropdown-item">
                    <i class="fas fa-eye"></i> {{ __('message.view') }}
                </a>
            @endcan

            @can('PostCategoryController@update')
                <a href="{{ route('post-categories.edit', $item->id) }}" class="btn btn-primary btn-sm dropdown-item">
                    <i class="far fa-edit"></i> {{ __('message.edit') }}
                </a>
            @endcan

            @can('PostCategoryController@destroy')
                {!! Form::open([
                    'method' => 'DELETE',
                    'route' => ['post-categories.destroy', $item->id],
                    'style' => 'display:inline',
                ]) !!}
                {!! Form::button('<i class="far fa-trash-alt"></i> ' . __('message.delete'), [
                    'type' => 'submit',
                    'class' => 'btn btn-danger btn-sm dropdown-item show_confirm',
                ]) !!}
                {!! Form::close() !!}
            @endcan
        </div>
    </td>
</tr>

{{-- render children --}}
@if ($item->children->count())
    @foreach ($item->children as $child)
        @include('admin.post_categories._row', [
            'item' => $child,
            'level' => $level + 1,
        ])
    @endforeach
@endif
