@props(['variant' => 'light'])

@php use App\Support\Link; @endphp

<header {{ $attributes->class(['site-header', 'site-header--'.$variant]) }} data-header>
    <div class="container site-header__inner">
        <a href="{{ route('home') }}" class="site-header__brand" aria-label="{{ config('kodu.name') }}, ir al inicio">
            <x-ui.logo eager />
        </a>

        <button type="button" class="site-header__toggle" aria-controls="menu-principal" aria-expanded="false" data-menu-toggle>
            <x-ui.icon name="menu" size="26" class="icon-open" />
            <x-ui.icon name="close" size="26" class="icon-close" />
            <span class="visually-hidden">Menú</span>
        </button>

        <nav id="menu-principal" class="site-nav" aria-label="Principal" data-menu>
            <ul class="site-nav__list">
                @foreach (config('kodu.nav') as $item)
                    @php $active = Link::active($item); @endphp
                    <li>
                        <a href="{{ Link::to($item) }}" @class(['site-nav__link', 'is-active' => $active]) @if ($active) aria-current="page" @endif>
                            {{ $item['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>
    </div>
</header>
