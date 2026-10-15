@php
    use App\Support\Link;
    $cartCount = app(\App\Support\Cart::class)->count();
    $advisorUrl = 'https://wa.me/'.config('kodu.contact.whatsapp').'?text='.rawurlencode(config('kodu.contact.whatsapp_text'));
@endphp

<header {{ $attributes->class('site-header') }} data-header>
    <div class="site-header__bar">
        <a href="{{ route('home') }}" class="site-header__brand" aria-label="{{ config('kodu.name') }}, ir al inicio">
            <x-ui.logo eager />
        </a>

        <nav id="menu-principal" class="site-nav" aria-label="Principal" data-menu>
            <ul class="site-nav__list">
                @foreach (Link::menu() as $item)
                    <li>
                        <a href="{{ $item['url'] }}" @class(['site-nav__link', 'is-active' => $item['active']]) @if ($item['active']) aria-current="page" @endif>
                            {{ $item['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
            <a href="{{ $advisorUrl }}" class="site-nav__cta btn-k btn-k--primary btn-k--md" target="_blank" rel="noopener">Habla con un asesor</a>
        </nav>

        <div class="site-header__actions">
            <a href="{{ route('cart') }}" @class(['cart-link', 'is-active' => request()->routeIs('cart')]) aria-label="Carrito, {{ $cartCount }} {{ $cartCount === 1 ? 'producto' : 'productos' }}">
                <x-ui.icon name="cart" size="22" />
                @if ($cartCount > 0)
                    <span class="cart-link__count">{{ $cartCount }}</span>
                @endif
            </a>

            <a href="{{ $advisorUrl }}" class="site-header__cta btn-k btn-k--primary" target="_blank" rel="noopener">Habla con un asesor</a>

            <button type="button" class="site-header__toggle" aria-controls="menu-principal" aria-expanded="false" data-menu-toggle>
                <x-ui.icon name="menu" size="26" class="icon-open" />
                <x-ui.icon name="close" size="26" class="icon-close" />
                <span class="visually-hidden">Menú</span>
            </button>
        </div>
    </div>
</header>
