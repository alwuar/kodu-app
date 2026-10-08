@props(['icon', 'title', 'href', 'link'])

<article {{ $attributes->class('service') }}>
    <x-ui.icon :name="$icon" class="service__icon" />
    <h3 class="service__title">{{ $title }}</h3>
    <p class="service__text">{{ $slot }}</p>
    <a href="{{ $href }}" class="service__link">{{ $link }}</a>
</article>
