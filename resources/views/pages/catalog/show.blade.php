@extends('layouts.app')

@section('title', $product['name'].' — '.$category['singular'])
@section('description', $product['summary'])
@section('og_type', 'product')
@if ($product['image'])
    @section('og_image', $product['image'])
@endif
@section('body_class', 'page-product')

@section('content')
    <article class="product" aria-labelledby="product-title">
        <div class="container">
            <x-ui.breadcrumb :items="[
                ['label' => 'Inicio', 'url' => route('home')],
                ['label' => $category['name'], 'url' => route('catalog.index', $category['slug'])],
                ['label' => $product['name']],
            ]" />

            <div class="product__hero">
                <div class="product__media">
                    <x-ui.image :src="$product['image']" :alt="$product['name'].', '.mb_strtolower($category['singular'])"
                                width="240" height="608" :lazy="false" priority class="product__img" />
                </div>

                <div class="product__info">
                    <p class="product__kicker">{{ $product['kicker'] }}</p>
                    <h1 id="product-title" class="product__title">{{ $product['name'] }}</h1>
                    <p class="product__summary">{{ $product['summary'] }}</p>
                    <x-ui.tags :tags="$product['tags']" variant="outline" class="product__tags" />

                    <form method="POST" action="{{ route('cart.store') }}" class="product__buy">
                        @csrf
                        <input type="hidden" name="product" value="{{ $product['slug'] }}">
                        <x-ui.button type="submit" size="sm">Agregar al carrito</x-ui.button>
                    </form>
                    <x-ui.flash key="cart_status" class="product__flash" />
                    <p class="product__note">El precio se muestra al agregar este producto a tu carrito.</p>

                    <a href="{{ $product['datasheet'] ? asset($product['datasheet']) : '#ficha-tecnica' }}"
                       class="product__download" @if ($product['datasheet']) download @endif>
                        <x-ui.icon name="download" size="16" />
                        Descargar ficha técnica (PDF)
                    </a>
                </div>
            </div>
        </div>

        <div class="container container--narrow" id="ficha-tecnica">
            <x-product.specs :specs="$product['specs']" />
        </div>
    </article>

    <x-sections.related :products="$related" />
    <x-sections.dealer-cta class="dealer-cta-section--product" />
@endsection

@push('schema')
    <x-site.json-ld :data="array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $product['name'],
        'description' => $product['summary'],
        'image' => $product['image'] ? asset($product['image']) : null,
        'sku' => strtoupper($product['slug']),
        'category' => $category['singular'],
        'brand' => ['@type' => 'Brand', 'name' => config('kodu.name')],
        'url' => url()->current(),
        'additionalProperty' => collect($product['specs'])->map(fn ($value, $name) => [
            '@type' => 'PropertyValue',
            'name' => $name,
            'value' => $value,
        ])->values()->all(),
    ])" />
@endpush
