<!DOCTYPE html>
<html lang="es-MX">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="turbo-refresh-scroll" content="preserve">
    {{-- Sin precarga al pasar el mouse: evita gastar avisos de un solo uso (flash) --}}
    <meta name="turbo-prefetch" content="false">

    <x-site.seo
        :title="trim($__env->yieldContent('title'))"
        :description="trim($__env->yieldContent('description'))"
        :image="trim($__env->yieldContent('og_image'))"
        :type="trim($__env->yieldContent('og_type')) ?: 'website'"
        :robots="trim($__env->yieldContent('robots')) ?: 'index, follow'"
    />

    <link rel="preload" href="{{ Vite::asset('resources/fonts/geist-latin.woff2') }}" as="font" type="font/woff2" crossorigin>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])

    @stack('head')
</head>
{{-- @section('header', 'dark'): la página empieza con un bloque oscuro que pasa por debajo de la barra --}}
<body @class([trim($__env->yieldContent('body_class')), 'has-hero' => trim($__env->yieldContent('header')) === 'dark'])>
    <a class="skip-link" href="#contenido">Saltar al contenido</a>

    <x-site.header />

    <main id="contenido">
        @yield('content')
    </main>

    <x-site.footer />

    <x-site.dealer-modal />

    <x-site.schema-organization />
    @stack('schema')
</body>
</html>
