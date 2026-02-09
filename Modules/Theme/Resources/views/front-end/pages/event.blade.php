@extends('theme::front-end.master')

@section('content')
    <div class="bg-[#f4f8ea]">
        @include('theme::front-end.components.top_event')
        @include('theme::front-end.components.list_event')
    </div>
@endsection
