@props(['video' => null, 'poster' => null, 'catalogUrl' => null])

@php
    $hero = config('kodu.hero');
    $videoPath = $video ? 'storage/'.$video : null;
    $hasVideo = $videoPath && is_file(public_path($videoPath));
    $posterPath = $poster && is_file(public_path('storage/'.$poster)) ? asset('storage/'.$poster) : null;
@endphp

<section @class(['hero', 'hero--video' => $hasVideo]) aria-labelledby="hero-title">
    @if ($hasVideo)
        <div class="hero__bg" aria-hidden="true">
            <video class="hero__video" autoplay muted loop playsinline preload="metadata"
                   @if ($posterPath) poster="{{ $posterPath }}" @endif
                   data-hero-video>
                <source src="{{ asset($videoPath) }}" type="{{ str_ends_with($videoPath, '.webm') ? 'video/webm' : 'video/mp4' }}">
            </video>
        </div>
    @endif

    <div class="container hero__inner">
        <div class="hero__content">
            <p class="hero__kicker">{{ $hero['kicker'] }}</p>
            <h1 id="hero-title" class="hero__title">{{ $hero['title'] }}</h1>
            <p class="hero__text">{{ $hero['text'] }}</p>
            <div class="hero__actions">
                <x-ui.button :href="$catalogUrl ?? route('home').'#catalogo'">Ver catálogo</x-ui.button>
                <x-ui.button :href="route('dealers')" variant="outline" data-dealer-open>Quiero ser Distribuidor KODU</x-ui.button>
            </div>
        </div>
    </div>
</section>
