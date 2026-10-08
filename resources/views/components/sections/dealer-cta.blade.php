@php $cta = config('kodu.dealer_cta'); @endphp

<section {{ $attributes->class('section dealer-cta-section') }} aria-labelledby="dealer-cta-title">
    <div class="container">
        <div class="dealer-cta">
            <div class="dealer-cta__content">
                <p class="dealer-cta__kicker">{{ $cta['kicker'] }}</p>
                <h2 id="dealer-cta-title" class="dealer-cta__title">{{ $cta['title'] }}</h2>
                <p class="dealer-cta__text">{{ $cta['text'] }}</p>
            </div>
            <x-ui.button :href="route('contact', ['asunto' => 'distribuidor'])" variant="dark" class="dealer-cta__btn">{{ $cta['button'] }}</x-ui.button>
        </div>
    </div>
</section>
