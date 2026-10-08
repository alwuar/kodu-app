@extends('layouts.app')

@section('title', $category['seo_title'])
@section('description', $category['seo_description'])
@section('body_class', 'page-catalog')

@section('content')
    <section class="catalog" aria-labelledby="catalog-title">
        <div class="container">
            <x-ui.breadcrumb :items="[
                ['label' => 'Inicio', 'url' => route('home')],
                ['label' => $category['name']],
            ]" />

            <header class="catalog__head">
                <div>
                    <h1 id="catalog-title" class="section-title">{{ $category['name'] }}</h1>
                    <p class="lead-sm catalog__text">{{ $category['description'] }}</p>
                </div>
                <p class="catalog__count" aria-live="polite">
                    <span data-filters-count>{{ $products->count() }}</span>
                    <span data-filters-count-label>{{ $products->count() === 1 ? 'elemento' : 'elementos' }}</span>
                </p>
            </header>

            <button type="button" class="catalog__filters-toggle" data-filters-open aria-controls="catalog-filters">
                Filtrar productos
            </button>

            <div class="catalog__layout" data-filters>
                <x-catalog.filters id="catalog-filters" :filters="$filters" class="catalog__filters" />

                <div class="catalog__results">
                    <ul class="catalog__grid">
                        @foreach ($products as $product)
                            <li data-product
                                data-machine="{{ $product['machine'] }}"
                                data-box="{{ $product['box'] }}"
                                data-tons-min="{{ $product['tons'][0] }}"
                                data-tons-max="{{ $product['tons'][1] }}">
                                <x-cards.product :product="$product" variant="light" heading-level="h2" />
                            </li>
                        @endforeach
                    </ul>
                    <p class="catalog__empty" data-filters-empty hidden>No hay productos con esos filtros.</p>
                </div>
            </div>
        </div>
    </section>

    <x-sections.dealer-cta class="dealer-cta-section--catalog" />
    <x-sections.services class="services--catalog" />
@endsection

@push('schema')
    <x-site.json-ld :data="[
        '@context' => 'https://schema.org',
        '@type' => 'CollectionPage',
        'name' => $category['name'],
        'description' => $category['seo_description'],
        'url' => url()->current(),
        'mainEntity' => [
            '@type' => 'ItemList',
            'numberOfItems' => $products->count(),
            'itemListElement' => $products->values()->map(fn ($p, $i) => [
                '@type' => 'ListItem',
                'position' => $i + 1,
                'url' => $p['url'],
                'name' => $p['name'],
            ])->all(),
        ],
    ]" />
@endpush
