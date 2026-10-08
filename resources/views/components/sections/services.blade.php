@php
    use App\Support\Link;
    $services = config('kodu.services');
@endphp

<section {{ $attributes->class('section services') }} aria-labelledby="services-title">
    <div class="container">
        <x-ui.section-title id="services-title" class="services__title">{{ $services['title'] }}</x-ui.section-title>

        <div class="services__grid">
            @foreach ($services['items'] as $item)
                <x-cards.service :icon="$item['icon']" :title="$item['title']" :href="Link::to($item)" :link="$item['link']">
                    {{ $item['text'] }}
                </x-cards.service>
            @endforeach
        </div>
    </div>
</section>
