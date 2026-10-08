@props(['products'])

@if ($products->isNotEmpty())
    <section class="section related" aria-labelledby="related-title">
        <div class="container">
            <h2 id="related-title" class="h-block related__title">Tal vez podría interesarte</h2>
            <div class="related__grid">
                @foreach ($products as $item)
                    <x-cards.product-row :product="$item" />
                @endforeach
            </div>
        </div>
    </section>
@endif
