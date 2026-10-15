@extends('layouts.admin')

@section('title', 'Pedido '.$order->number)

@php use App\Support\Money; @endphp

@section('content')
    <div class="admin__head">
        <h1>Pedido {{ $order->number }} <x-admin.status :status="$order->status" :label="$order->statusLabel()" /></h1>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">Volver</a>
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="card">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr><th>Producto</th><th class="text-end">Precio</th><th class="text-end">Cant.</th><th class="text-end">Total</th></tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td>
                                        @if ($item->product)
                                            <a href="{{ route('admin.products.edit', $item->product) }}">{{ $item->name }}</a>
                                        @else
                                            {{ $item->name }}
                                        @endif
                                        @if ($item->sku)<br><small class="text-secondary">{{ $item->sku }}</small>@endif
                                    </td>
                                    <td class="text-end">{{ Money::format($item->unit_price) }}</td>
                                    <td class="text-end">{{ $item->quantity }}</td>
                                    <td class="text-end">{{ Money::format($item->total) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr><th colspan="3" class="text-end">Total</th><th class="text-end">{{ Money::format($order->total) }}</th></tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="row g-4 mt-0">
                <div class="col-md-6">
                    <div class="card card-body h-100">
                        <h2 class="h6">Cliente</h2>
                        <p class="mb-1">{{ $order->customer_name ?: '—' }}</p>
                        @if ($order->customer_email)<p class="mb-1"><a href="mailto:{{ $order->customer_email }}">{{ $order->customer_email }}</a></p>@endif
                        @if ($order->customer_phone)<p class="mb-0"><a href="tel:{{ $order->customer_phone }}">{{ $order->customer_phone }}</a></p>@endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-body h-100">
                        <h2 class="h6">Dirección de envío</h2>
                        @forelse ($order->shippingLines() as $line)
                            <p class="mb-1">{{ $line }}</p>
                        @empty
                            <p class="text-secondary mb-0">Sin dirección.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="card card-body">
                @csrf
                @method('PATCH')
                <div class="mb-3">
                    <label for="status" class="form-label">Estado</label>
                    <select id="status" name="status" class="form-select">
                        @foreach (\App\Models\Order::STATUSES as $key => $label)
                            <option value="{{ $key }}" @selected($order->status === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <x-admin.input name="notes" label="Notas internas" type="textarea" :value="$order->notes" />
                <button type="submit" class="btn btn-primary">Guardar</button>
            </form>

            <div class="card card-body mt-4 small">
                <p class="mb-1"><strong>Creado:</strong> {{ $order->created_at->timezone('America/Merida')->format('d/m/Y H:i') }}</p>
                <p class="mb-1"><strong>Pagado:</strong> {{ $order->paid_at?->timezone('America/Merida')->format('d/m/Y H:i') ?? '—' }}</p>
                @if ($order->stripe_payment_intent)
                    <p class="mb-0"><strong>Stripe:</strong>
                        <a href="https://dashboard.stripe.com/payments/{{ $order->stripe_payment_intent }}" target="_blank" rel="noopener">{{ $order->stripe_payment_intent }} ↗</a>
                    </p>
                @endif
            </div>
        </div>
    </div>
@endsection
