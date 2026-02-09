@extends('theme::front-end.master')

@section('content')
  @include('theme::front-end.components.slide')
  @include('theme::front-end.components.about_us')
  @include('theme::front-end.components.stats')
  @include('theme::front-end.components.featured_services')
  @include('theme::front-end.components.promo')
  @include('theme::front-end.components.reason')
  @include('theme::front-end.components.space_gallery')
  @include('theme::front-end.components.booking')
  @include('theme::front-end.components.promo_customer_review')
@endsection