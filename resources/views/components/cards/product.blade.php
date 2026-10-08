@props(['product', 'variant' => 'dark', 'headingLevel' => 'h3'])

<article {{ $attributes->class(['product-card', 'product-card--'.$variant]) }}>
    <a href="{{ $product['url'] }}" class="product-card__media" tabindex="-1" aria-hidden="true">
        <x-ui.image :src="$product['thumb'] ?? $product['image']" :alt="$product['name']" width="120" height="304" class="product-card__img" />
    </a>
    <div class="product-card__body">
        <{{ $headingLevel }} class="product-card__name">
            <a href="{{ $product['url'] }}">{{ $product['name'] }}</a>
        </{{ $headingLevel }}>
        <x-ui.tags :tags="$product['tags']" class="product-card__tags" />
        <a href="{{ $product['url'] }}" class="link-arrow">
            Ver ficha técnica <span class="link-arrow__icon" aria-hidden="true">→</span>
            <span class="visually-hidden">de {{ $product['name'] }}</span>
        </a>
    </div>
</article>
