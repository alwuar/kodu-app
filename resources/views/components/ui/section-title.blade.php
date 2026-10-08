@props(['as' => 'h2'])

<{{ $as }} {{ $attributes->class('section-title') }}>{{ $slot }}</{{ $as }}>
