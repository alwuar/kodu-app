@props(['product'])

<article {{ $attributes->class('product-row') }}>
    <a href="{{ $product['url'] }}" class="product-row__media" tabindex="-1" aria-hidden="true">
        <x-ui.image :src="$product['thumb'] ?? $product['image']" :alt="$product['name']" width="120" height="304" class="product-row__img" />
    </a>
    <div class="product-row__body">
        <h3 class="product-card__name"><a href="{{ $product['url'] }}">{{ $product['name'] }}</a></h3>
        <x-ui.tags :tags="$product['tags']" class="product-card__tags" />
        <a href="{{ $product['url'] }}" class="link-arrow">
            Ver ficha técnica <span class="link-arrow__icon" aria-hidden="true">→</span>
            <span class="visually-hidden">de {{ $product['name'] }}</span>
        </a>
    </div>
</article>
