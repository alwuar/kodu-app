@extends('layouts.app')

@section('header', 'dark')
@section('body_class', 'page-home')

@section('content')
    <x-sections.hero :video="$heroVideo" :poster="$heroPoster" :catalog-url="$category?->url()" />
    <x-sections.why />
    <x-sections.dealer-cta class="dealer-cta-section--home" />
    @if ($category && $products->isNotEmpty())
        <x-sections.product-carousel id="catalogo" :category="$category" :products="$products" />
    @endif
    <x-sections.services class="services--home" />
@endsection
