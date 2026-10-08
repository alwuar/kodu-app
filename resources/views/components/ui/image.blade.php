@props([
    'src' => null,
    'alt' => '',
    'width' => null,
    'height' => null,
    'lazy' => true,
    'priority' => false,
    'sizes' => null,
    'srcset' => null,
])

@php
    $isRemote = $src && str_starts_with($src, 'http');
    $exists = $src && ($isRemote || is_file(public_path($src)));
@endphp

@if ($exists)
    <img src="{{ $isRemote ? $src : asset($src) }}"
         @if ($srcset) srcset="{{ $srcset }}" @endif
         @if ($sizes) sizes="{{ $sizes }}" @endif
         alt="{{ $alt }}"
         @if ($width) width="{{ $width }}" @endif
         @if ($height) height="{{ $height }}" @endif
         @if ($priority) fetchpriority="high" @elseif ($lazy) loading="lazy" @endif
         decoding="async"
         {{ $attributes->class('img') }}>
@else
    <span role="img" aria-label="{{ $alt }}" {{ $attributes->class(['img', 'img-placeholder']) }}
          @if ($width && $height) style="aspect-ratio: {{ $width }} / {{ $height }}" @endif></span>
@endif
