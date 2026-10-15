@props(['variant' => 'light'])

@php
    use App\Support\Link;
    $cartCount = app(\App\Support\Cart::class)->count();
@endphp

<header {{ $attributes->class(['site-header', 'site-header--'.$variant]) }} data-header>
    <div class="container site-header__inner">
        <a href="{{ route('home') }}" class="site-header__brand" aria-label="{{ config('kodu.name') }}, ir al inicio">
            <x-ui.logo eager />
        </a>

        <div class="site-header__actions">
            <a href="{{ route('cart') }}" @class(['cart-link', 'is-active' => request()->routeIs('cart')]) aria-label="Carrito, {{ $cartCount }} {{ $cartCount === 1 ? 'producto' : 'productos' }}">
                <x-ui.icon name="cart" size="20" />
                @if ($cartCount > 0)
                    <span class="cart-link__count">{{ $cartCount }}</span>
                @endif
            </a>

            <button type="button" class="site-header__toggle" aria-controls="menu-principal" aria-expanded="false" data-menu-toggle>
                <x-ui.icon name="menu" size="26" class="icon-open" />
                <x-ui.icon name="close" size="26" class="icon-close" />
                <span class="visually-hidden">Menú</span>
            </button>
        </div>

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
        </nav>
    </div>
</header>
