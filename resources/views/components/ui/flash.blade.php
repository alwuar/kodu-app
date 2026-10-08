@props(['key' => 'status'])

@if (session($key))
    <p role="status" {{ $attributes->class('flash') }}>{{ session($key) }}</p>
@endif
