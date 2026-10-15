@extends('layouts.app')

@section('title', 'Distribuidores')
@section('description', 'Vende KODU en tu zona: sin stock mínimo y sin altos volúmenes. Conoce por qué ser distribuidor y envía tu solicitud.')
@section('header', 'dark')
@section('body_class', 'page-dealers')

@php $d = config('kodu.dealers'); @endphp

@section('content')
    <section class="dealers-hero" aria-labelledby="dealers-title">
        <div class="container">
            <x-ui.breadcrumb class="breadcrumb-k--dark" :items="[
                ['label' => 'Inicio', 'url' => route('home')],
                ['label' => 'Distribuidores'],
            ]" />
            <p class="hero__kicker dealers-hero__kicker">{{ $d['kicker'] }}</p>
            <h1 id="dealers-title" class="hero__title dealers-hero__title">{{ $d['title'] }}</h1>
            <p class="hero__text dealers-hero__text">{{ $d['text'] }}</p>
            <div class="hero__actions">
                <x-ui.button href="#solicitud">Quiero ser distribuidor</x-ui.button>
            </div>
        </div>
    </section>

    <section class="section dealers-why" aria-labelledby="dealers-why-title">
        <div class="container">
            <x-ui.section-title id="dealers-why-title">{{ $d['why_title'] }}</x-ui.section-title>
            <div class="dealers-why__grid">
                @foreach ($d['benefits'] as $item)
                    <x-cards.feature :icon="$item['icon']" :title="$item['title']">{{ $item['text'] }}</x-cards.feature>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section dealers-steps" aria-labelledby="dealers-steps-title">
        <div class="container">
            <x-ui.section-title id="dealers-steps-title">{{ $d['steps_title'] }}</x-ui.section-title>
            <ol class="dealers-steps__list">
                @foreach ($d['steps'] as $step)
                    <li class="dealers-steps__item">
                        <span class="dealers-steps__num" aria-hidden="true">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <h3 class="service__title">{{ $step['title'] }}</h3>
                        <p class="service__text">{{ $step['text'] }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    <section id="solicitud" class="section dealers-form" aria-labelledby="dealers-form-title">
        <div class="container">
            <div class="dealers-form__layout">
                <div>
                    <x-ui.section-title id="dealers-form-title">{{ $d['form_title'] }}</x-ui.section-title>
                    <p class="lead-sm dealers-form__text">{{ $d['form_text'] }}</p>
                    <ul class="dealers-form__contact">
                        <li><a href="tel:{{ config('kodu.contact.phone_e164') }}">{{ config('kodu.contact.phone') }}</a></li>
                        <li><a href="mailto:{{ config('kodu.contact.email') }}">{{ config('kodu.contact.email') }}</a></li>
                    </ul>
                </div>
                <x-forms.dealer origin="pagina" prefix="dp" />
            </div>
        </div>
    </section>
@endsection
