@props(['product', 'variant' => 'dark', 'headingLevel' => 'h3'])

@php $url = $product->url(); @endphp

<article {{ $attributes->class(['product-card', 'product-card--'.$variant]) }}>
    <a href="{{ $url }}" class="product-card__media" tabindex="-1" aria-hidden="true">
        <x-ui.image :src="$product->imagePath()" :alt="$product->name" width="120" height="304" class="product-card__img" />
    </a>
    <div class="product-card__body">
        <{{ $headingLevel }} class="product-card__name">
            <a href="{{ $url }}">{{ $product->name }}</a>
        </{{ $headingLevel }}>
        <x-ui.tags :tags="$product->tags()" class="product-card__tags" />
        <a href="{{ $url }}" class="link-arrow">
            Ver ficha técnica <span class="link-arrow__icon" aria-hidden="true">→</span>
            <span class="visually-hidden">de {{ $product->name }}</span>
        </a>
    </div>
</article>
