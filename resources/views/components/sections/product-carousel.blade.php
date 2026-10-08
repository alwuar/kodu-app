@props(['category', 'products'])

<section class="section product-carousel" aria-labelledby="carousel-title">
    <div class="container">
        <x-ui.section-title id="carousel-title" class="product-carousel__title">
            <a href="{{ route('catalog.index', $category['slug']) }}">{{ $category['name'] }}</a>
        </x-ui.section-title>
        <p class="lead-sm product-carousel__text">{{ $category['description'] }}</p>
    </div>

    <div class="carousel" data-carousel>
        <ul class="carousel__track" data-carousel-track tabindex="0" aria-label="{{ $category['name'] }}">
            @foreach ($products as $product)
                <li class="carousel__item">
                    <x-cards.product :product="$product" />
                </li>
            @endforeach
        </ul>
    </div>
</section>
