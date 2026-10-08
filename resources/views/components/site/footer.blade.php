@php
    use App\Support\Link;
    $contact = config('kodu.contact');
@endphp

<footer class="site-footer">
    <div class="container site-footer__grid">
        <div class="site-footer__brand">
            <a href="{{ route('home') }}" aria-label="{{ config('kodu.name') }}, ir al inicio">
                <x-ui.logo class="site-footer__logo" />
            </a>
            <p class="site-footer__text">{{ config('kodu.footer_text') }}</p>
            <x-site.social class="site-footer__social" />
        </div>

        <nav class="site-footer__col" aria-labelledby="footer-paginas">
            <h2 id="footer-paginas" class="site-footer__title">Páginas</h2>
            <ul class="site-footer__list">
                @foreach (config('kodu.footer_nav') as $item)
                    <li><a href="{{ Link::to($item) }}">{{ $item['label'] }}</a></li>
                @endforeach
            </ul>
        </nav>

        <div class="site-footer__col">
            <h2 class="site-footer__title">Contacto</h2>
            <ul class="site-footer__list">
                <li><a href="mailto:{{ $contact['email'] }}">{{ $contact['email'] }}</a></li>
                <li><a href="tel:{{ $contact['phone_e164'] }}">{{ $contact['phone'] }}</a></li>
                <li><address>{{ $contact['address'] }}</address></li>
            </ul>
        </div>

        <div class="site-footer__col">
            <h2 class="site-footer__title">Cobertura de entrega</h2>
            <ul class="site-footer__list">
                @foreach (config('kodu.coverage') as $city)
                    <li>{{ $city }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</footer>
