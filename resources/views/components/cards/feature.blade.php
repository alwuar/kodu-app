@props(['icon', 'title'])

<article {{ $attributes->class('feature') }}>
    <x-ui.icon :name="$icon" class="feature__icon" />
    <h3 class="feature__title">{{ $title }}</h3>
    <p class="feature__text">{{ $slot }}</p>
</article>
