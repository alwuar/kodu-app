@props([
    'title' => '',
    'description' => '',
    'image' => '',
    'type' => 'website',
    'robots' => 'index, follow',
])

@php
    $siteName = config('kodu.legal_name');
    $fullTitle = $title ? $title.' | '.config('kodu.name') : config('kodu.name').' — '.config('kodu.hero.title');
    $description = $description ?: config('kodu.description');
    $canonical = url()->current();
    $image = asset($image ?: config('kodu.og_image'));
@endphp

<title>{{ $fullTitle }}</title>
<meta name="description" content="{{ $description }}">
<meta name="robots" content="{{ $robots }}">
<link rel="canonical" href="{{ $canonical }}">

<meta name="theme-color" content="{{ config('kodu.theme_color') }}">
<link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
<link rel="icon" href="{{ asset('images/favicon.svg') }}" type="image/svg+xml">

<meta property="og:locale" content="{{ config('kodu.locale') }}">
<meta property="og:type" content="{{ $type }}">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:title" content="{{ $fullTitle }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:image" content="{{ $image }}">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $fullTitle }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $image }}">
