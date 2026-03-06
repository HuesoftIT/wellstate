@extends('theme::front-end.master')
@section('title')
    <title>{{ '404: Page not found - ' . $settings['meta_title'] }}</title>
    <META NAME="KEYWORDS" content="{{ $settings['meta_keyword'] }}" />
    <meta name="description" content="{{ $settings['meta_description'] }}" />
@endsection
@section('schema')
    <script type="application/ld+json">
        {
         "@context": "http://schema.org",
         "@type": "BreadcrumbList",
         "itemListElement":
         [
          {
           "@type": "ListItem",
           "position": 1,
           "item":
           {
            "@id": "{{ url('/')}}",
            "name": "{{ trans('theme::frontend.home.home') }}"
            }
          },
          {
           "@type": "ListItem",
          "position": 2,
          "item":
           {
             "@id": "{{ Request::fullUrl() }}",
             "name": "{{ trans('theme::frontend.error_page.not_found') }}"
           }
          }
         ]
        }
    </script>
@endsection
@section('breadcrumb')
    <div class="breadcrumb breadcrumb-fixed justify-content-center">
        <a href="{{ url('/') }}">{{ trans('theme::frontend.home') }}</a>
        <i class="fas fa-long-arrow-alt-right" aria-hidden="true"></i>
        <span>{{ trans('theme::frontend.error_page.not_found') }}</span>
    </div>
@endsection
@section('content')
    <div class="min-h-[70vh] flex items-center justify-center px-6">

        <div class="text-center max-w-xl">

            <!-- 404 -->
            <h1 class="text-[120px] font-bold text-[#FFDC97] leading-none">
                404
            </h1>

            <!-- Title -->
            <h2 class="text-2xl md:text-3xl font-semibold text-gray-800 mt-4">
                {{ trans('theme::frontend.error_page.not_found') }}
            </h2>

            <!-- Description -->
            <p class="text-gray-500 mt-4 leading-relaxed">
                {{ trans('theme::frontend.error_page.sorry_page') }}. <br>
                Trang bạn đang tìm có thể đã bị xóa, đổi tên hoặc tạm thời không tồn tại.
            </p>

            <!-- Buttons -->
            <div class="flex justify-center gap-4 mt-8 flex-wrap">

                <!-- Home -->
                <a href="{{ url('/') }}"
                    class="px-6 py-3 rounded-full bg-[#FFDC97] text-black font-medium hover:opacity-90 transition">
                    {{ trans('theme::frontend.home') }}
                </a>

                <!-- Back -->
                <button onclick="history.back()"
                    class="px-6 py-3 rounded-full border border-gray-300 text-gray-700 hover:bg-gray-100 transition">
                    Quay lại trang trước
                </button>

            </div>

        </div>

    </div>
@endsection
