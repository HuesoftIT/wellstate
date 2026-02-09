@extends('theme::front-end.master')

@section('content')
    @include('theme::front-end.pages.services.top_detail_service')
    <div class="bg-[#f4f8ea]">
        <div class="container mx-auto px-6 pt-6 ">
            <h3 class="text-center text-[32px] font-open-sans uppercase font-medium">Có thể bạn quan tâm</h3>
        </div>
        @include('theme::front-end.pages.services.list')
    </div>
@endsection
