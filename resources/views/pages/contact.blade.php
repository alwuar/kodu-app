@extends('layouts.app')

@section('title', $subject === 'distribuidor' ? 'Distribuidores' : 'Contacto')
@section('description', $subject === 'distribuidor'
    ? 'Vende KODU en tu zona: sin stock mínimo y sin altos volúmenes. Déjanos tus datos y te decimos cómo.'
    : 'Contacta a KODU para cotizar martillos hidráulicos, accesorios y refacciones. '.config('kodu.contact.phone').' · '.config('kodu.contact.email'))
@section('body_class', 'page-contact')

@php $contact = config('kodu.contact'); @endphp

@section('content')
    <section class="contact" aria-labelledby="contact-title">
        <div class="container">
            <h1 id="contact-title" class="visually-hidden">
                {{ $subject === 'distribuidor' ? 'Quiero ser distribuidor KODU' : 'Contacto' }}
            </h1>

            <div class="contact__layout">
                <div class="contact__info">
                    <dl class="contact__list">
                        <div>
                            <dt>Email</dt>
                            <dd><a href="mailto:{{ $contact['email'] }}">{{ $contact['email'] }}</a></dd>
                        </div>
                        <div>
                            <dt>Teléfono</dt>
                            <dd><a href="tel:{{ $contact['phone_e164'] }}">{{ $contact['phone'] }}</a></dd>
                        </div>
                        <div>
                            <dt>Dirección</dt>
                            <dd><address>{{ $contact['address'] }}</address></dd>
                        </div>
                    </dl>

                    <div class="contact__social">
                        <span class="contact__social-label">Síguenos</span>
                        <x-site.social size="22" />
                    </div>
                </div>

                <x-forms.contact :subject="$subject" class="contact__form" />
            </div>
        </div>
    </section>

    <x-sections.dealer-cta class="dealer-cta-section--contact" />
@endsection

@push('schema')
    <x-site.json-ld :data="[
        '@context' => 'https://schema.org',
        '@type' => 'ContactPage',
        'name' => 'Contacto KODU',
        'url' => url()->current(),
        'about' => ['@id' => url('/').'#organizacion'],
    ]" />
@endpush
