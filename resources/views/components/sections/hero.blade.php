@php $hero = config('kodu.hero'); @endphp

<section class="hero" aria-labelledby="hero-title">
    <div class="container hero__inner">
        <div class="hero__content">
            <p class="hero__kicker">{{ $hero['kicker'] }}</p>
            <h1 id="hero-title" class="hero__title">{{ $hero['title'] }}</h1>
            <p class="hero__text">{{ $hero['text'] }}</p>
            <div class="hero__actions">
                <x-ui.button :href="route('catalog.index', 'martillos-hidraulicos')">Ver catálogo</x-ui.button>
                <x-ui.button :href="route('contact', ['asunto' => 'distribuidor'])" variant="outline">Quiero ser Distribuidor KODU</x-ui.button>
            </div>
        </div>

        @if ($hero['image'])
            <div class="hero__media">
                <x-ui.image :src="$hero['image']" alt="" width="560" height="420" priority />
            </div>
        @endif
    </div>
</section>
