@props(['items' => []])

@php
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => collect($items)->values()->map(fn ($item, $i) => array_filter([
            '@type' => 'ListItem',
            'position' => $i + 1,
            'name' => $item['label'],
            'item' => $item['url'] ?? url()->current(),
        ]))->all(),
    ];
@endphp

<nav aria-label="Ruta de navegación" {{ $attributes->class('breadcrumb-k') }}>
    <ol>
        @foreach ($items as $item)
            <li>
                @if (! $loop->last && ! empty($item['url']))
                    <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                @else
                    <span aria-current="page">{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>

@push('schema')
    <x-site.json-ld :data="$schema" />
@endpush
