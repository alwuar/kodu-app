@extends('layouts.app')

@section('title', 'Carrito')
@section('robots', 'noindex, nofollow')
@section('body_class', 'page-cart')

@php use App\Support\Money; use App\Support\Cart; @endphp

@section('content')
    <section class="cart" aria-labelledby="cart-title">
        <div class="container">
            <x-ui.breadcrumb :items="[
                ['label' => 'Inicio', 'url' => route('home')],
                ['label' => 'Carrito'],
            ]" />

            <h1 id="cart-title" class="section-title cart__title">Carrito</h1>

            <x-ui.flash key="cart_status" class="cart__flash" />
            @if (session('cart_error'))
                <p role="alert" class="flash flash--error cart__flash">{{ session('cart_error') }}</p>
            @endif

            @if ($lines->isEmpty())
                <div class="cart__empty">
                    <p>Tu carrito está vacío.</p>
                    <x-ui.button :href="route('home').'#catalogo'" size="md">Ver catálogo</x-ui.button>
                </div>
            @else
                <div class="cart__layout">
                    <ul class="cart__lines">
                        @foreach ($lines as $line)
                            @php $product = $line['product']; @endphp
                            <li class="cart-line">
                                <a href="{{ $product->url() }}" class="cart-line__media" tabindex="-1" aria-hidden="true">
                                    <x-ui.image :src="$product->imagePath()" :alt="$product->name" width="120" height="304" />
                                </a>

                                <div class="cart-line__info">
                                    <h2 class="cart-line__name"><a href="{{ $product->url() }}">{{ $product->name }}</a></h2>
                                    <x-ui.tags :tags="$product->tags()" class="cart-line__tags" />
                                    <p class="cart-line__unit">{{ Money::format($line['unit_price']) }} c/u</p>
                                </div>

                                <form method="POST" action="{{ route('cart.update', $product) }}" class="cart-line__qty" data-cart-qty>
                                    @csrf
                                    @method('PATCH')
                                    <label class="visually-hidden" for="qty-{{ $product->id }}">Cantidad de {{ $product->name }}</label>
                                    <button type="submit" name="step" value="-1" class="qty-btn" aria-label="Quitar uno">−</button>
                                    <input id="qty-{{ $product->id }}" type="number" name="quantity" value="{{ $line['quantity'] }}" min="1" max="{{ Cart::MAX_QUANTITY }}" inputmode="numeric" class="qty-input">
                                    <button type="submit" name="step" value="1" class="qty-btn" aria-label="Agregar uno" @disabled($line['quantity'] >= Cart::MAX_QUANTITY)>+</button>
                                </form>

                                <p class="cart-line__total">{{ Money::format($line['total']) }}</p>

                                <form method="POST" action="{{ route('cart.destroy', $product) }}" class="cart-line__remove">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" aria-label="Quitar {{ $product->name }} del carrito">
                                        <x-ui.icon name="trash" size="18" />
                                    </button>
                                </form>
                            </li>
                        @endforeach
                    </ul>

                    <aside class="cart-summary" aria-labelledby="summary-title">
                        <h2 id="summary-title" class="cart-summary__title">Resumen</h2>
                        <dl class="cart-summary__rows">
                            <div><dt>Subtotal</dt><dd>{{ Money::format($subtotal) }}</dd></div>
                            <div><dt>Envío</dt><dd>Se coordina con un asesor</dd></div>
                            <div class="cart-summary__total"><dt>Total</dt><dd>{{ Money::format($subtotal) }}</dd></div>
                        </dl>
                        <p class="cart-summary__note">Precios en pesos mexicanos, IVA incluido.</p>

                        <form method="POST" action="{{ route('checkout') }}" data-turbo="false">
                            @csrf
                            <x-ui.button type="submit" size="md" class="cart-summary__pay">Pagar con tarjeta</x-ui.button>
                        </form>
                        <p class="cart-summary__secure">
                            <x-ui.icon name="lock" size="14" /> Pago seguro con Stripe
                        </p>
                    </aside>
                </div>
            @endif
        </div>
    </section>
@endsection
