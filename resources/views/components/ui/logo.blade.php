@props(['eager' => false])

<img src="{{ asset(config('kodu.logo')) }}"
     alt="{{ config('kodu.name') }} — {{ config('kodu.tagline') }}"
     width="148" height="38"
     @unless ($eager) loading="lazy" @endunless
     decoding="async"
     {{ $attributes->class('logo') }}>
