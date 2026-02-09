@extends('theme::front-end.master')

@section('content')
    <div class="bg-[#f4f8ea]">
        @include('theme::front-end.components.top_promotion')
        @include('theme::front-end.components.list_promotion')
    </div>
@endsection
