@php use App\Support\Money; @endphp
<x-mail.layout>
    @if ($forStore)
        <h1 style="font-size:20px;margin:0 0 12px;">Nuevo pedido pagado</h1>
        <p style="margin:0 0 16px;">El pedido <strong>{{ $order->number }}</strong> se pagó con Stripe. Revísalo en el <a href="{{ route('admin.orders.show', $order) }}" style="color:#e8391c;">panel de administración</a>.</p>
    @else
        <h1 style="font-size:20px;margin:0 0 12px;">¡Gracias por tu compra{{ $order->customer_name ? ', '.$order->customer_name : '' }}!</h1>
        <p style="margin:0 0 16px;">Recibimos el pago de tu pedido <strong>{{ $order->number }}</strong>. Un asesor de KODU te contactará para coordinar la entrega.</p>
    @endif

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin:0 0 16px;">
        @foreach ($order->items as $item)
            <tr>
                <td style="padding:8px 0;border-bottom:1px solid #eee;">{{ $item->quantity }} × {{ $item->name }}</td>
                <td style="padding:8px 0;border-bottom:1px solid #eee;text-align:right;">{{ Money::format($item->total) }}</td>
            </tr>
        @endforeach
        <tr>
            <td style="padding:10px 0;font-weight:bold;">Total</td>
            <td style="padding:10px 0;font-weight:bold;text-align:right;">{{ Money::format($order->total) }}</td>
        </tr>
    </table>

    <p style="margin:0 0 4px;"><strong>Cliente:</strong> {{ $order->customer_name }} · {{ $order->customer_email }} · {{ $order->customer_phone }}</p>
    @if ($order->shippingLines())
        <p style="margin:0;"><strong>Envío:</strong> {{ implode(', ', $order->shippingLines()) }}</p>
    @endif
</x-mail.layout>
