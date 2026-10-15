@extends('layouts.app')

@section('title', 'Pedido '.$order->number)
@section('robots', 'noindex, nofollow')
@section('body_class', 'page-order')

@php use App\Support\Money; @endphp

@section('content')
    <section class="order-done" aria-labelledby="order-title">
        <div class="container">
            <div class="order-done__box">
                @if ($order->isPaid())
                    <x-ui.icon name="check-circle" size="40" class="order-done__icon" />
                    <h1 id="order-title" class="section-title">¡Gracias por tu compra!</h1>
                    <p class="lead-sm">Recibimos el pago de tu pedido <strong>{{ $order->number }}</strong>.
                        @if ($order->customer_email) Te enviamos la confirmación a {{ $order->customer_email }}. @endif
                        Un asesor de KODU te contactará para coordinar la entrega.</p>
                @else
                    <h1 id="order-title" class="section-title">Estamos confirmando tu pago</h1>
                    <p class="lead-sm">Tu pedido <strong>{{ $order->number }}</strong> quedó registrado. En cuanto Stripe confirme el pago te enviaremos un correo. Si pagaste en efectivo o transferencia, puede tardar un poco más.</p>
                @endif

                <table class="order-done__table">
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td>{{ $item->quantity }} × {{ $item->name }}</td>
                                <td>{{ Money::format($item->total) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th scope="row">Total</th>
                            <td>{{ Money::format($order->total) }}</td>
                        </tr>
                    </tfoot>
                </table>

                <x-ui.button :href="route('home')" size="md">Volver al inicio</x-ui.button>
            </div>
        </div>
    </section>
@endsection
