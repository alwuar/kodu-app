@props(['href' => null, 'variant' => 'primary', 'size' => 'lg', 'type' => 'button'])

@php
    $classes = ['btn-k', 'btn-k--'.$variant, 'btn-k--'.$size];
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->class($classes) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->class($classes) }}>{{ $slot }}</button>
@endif
