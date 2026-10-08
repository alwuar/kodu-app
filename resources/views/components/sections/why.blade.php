@php $why = config('kodu.why'); @endphp

<section id="nosotros" class="section why" aria-labelledby="why-title">
    <div class="container">
        <x-ui.section-title id="why-title" class="why__title">{{ $why['title'] }}</x-ui.section-title>

        <div class="why__grid">
            @foreach ($why['items'] as $item)
                <x-cards.feature :icon="$item['icon']" :title="$item['title']">{{ $item['text'] }}</x-cards.feature>
            @endforeach
        </div>
    </div>
</section>
