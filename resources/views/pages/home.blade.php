@extends('layouts.app')

@section('header', 'dark')
@section('body_class', 'page-home')

@section('content')
    <x-sections.hero />
    <x-sections.why />
    <x-sections.dealer-cta class="dealer-cta-section--home" />
    <x-sections.product-carousel :category="$category" :products="$products" />
    <x-sections.services class="services--home" />
@endsection
