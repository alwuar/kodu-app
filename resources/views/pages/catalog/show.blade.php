@extends('layouts.app')

@section('title', $product->name.' — '.$category->singularName())
@section('description', Str::limit((string) $product->summary, 160))
@section('og_type', 'product')
@if ($product->imagePath())
    @section('og_image', $product->imagePath())
@endif
@section('body_class', 'page-product')

@php $datasheet = $product->datasheetUrl(); @endphp

@section('content')
    <article class="product" aria-labelledby="product-title">
        <div class="container">
            <x-ui.breadcrumb :items="[
                ['label' => 'Inicio', 'url' => route('home')],
                ['label' => $category->name, 'url' => $category->url()],
                ['label' => $product->name],
            ]" />

            <div class="product__hero">
                <div class="product__media">
                    <x-ui.image :src="$product->imagePath()" :alt="$product->name.', '.mb_strtolower($category->singularName())"
                                width="240" height="608" :lazy="false" priority class="product__img" />
                </div>

                <div class="product__info">
                    <p class="product__kicker">{{ $product->kickerText() }}</p>
                    <h1 id="product-title" class="product__title">{{ $product->name }}</h1>
                    <p class="product__summary">{{ $product->summary }}</p>
                    <x-ui.tags :tags="$product->tags()" variant="outline" class="product__tags" />

                    @if ($product->isPurchasable())
                        <form method="POST" action="{{ route('cart.store') }}" class="product__buy">
                            @csrf
                            <input type="hidden" name="product" value="{{ $product->id }}">
                            <x-ui.button type="submit" size="sm">Agregar al carrito</x-ui.button>
                        </form>
                        <p class="product__note">El precio se muestra al agregar este producto a tu carrito.</p>
                    @else
                        <div class="product__buy">
                            <x-ui.button :href="route('contact')" size="sm">Solicitar cotización</x-ui.button>
                        </div>
                        <p class="product__note">Contáctanos para conocer el precio y disponibilidad.</p>
                    @endif

                    @if ($datasheet)
                        <a href="{{ $datasheet }}" class="product__download" target="_blank" rel="noopener" data-turbo="false">
                            <x-ui.icon name="download" size="16" />
                            Descargar ficha técnica (PDF)
                        </a>
                    @endif
                </div>
            </div>
        </div>

        @if ($product->specs)
            <div class="container container--narrow" id="ficha-tecnica">
                <x-product.specs :specs="$product->specs" />
            </div>
        @endif
    </article>

    <x-sections.related :products="$related" />
    <x-sections.dealer-cta class="dealer-cta-section--product" />
@endsection

@push('schema')
    <x-site.json-ld :data="array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $product->name,
        'description' => $product->summary,
        'image' => $product->imagePath() ? asset($product->imagePath()) : null,
        'sku' => $product->sku ?: strtoupper($product->slug),
        'category' => $category->singularName(),
        'brand' => ['@type' => 'Brand', 'name' => config('kodu.name')],
        'url' => url()->current(),
        'additionalProperty' => collect($product->specs ?? [])->map(fn ($row) => [
            '@type' => 'PropertyValue',
            'name' => $row['label'],
            'value' => $row['value'],
        ])->values()->all(),
    ])" />
@endpush
