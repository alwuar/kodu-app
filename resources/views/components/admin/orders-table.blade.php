@props(['orders'])

<div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead>
            <tr>
                <th>Pedido</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th class="text-end">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td><a href="{{ route('admin.orders.show', $order) }}" class="fw-semibold">{{ $order->number }}</a></td>
                    <td>{{ $order->customer_name ?: '—' }}<br><small class="text-secondary">{{ $order->customer_email }}</small></td>
                    <td>{{ ($order->paid_at ?? $order->created_at)->timezone('America/Merida')->format('d/m/Y H:i') }}</td>
                    <td><x-admin.status :status="$order->status" :label="$order->statusLabel()" /></td>
                    <td class="text-end">{{ $order->formattedTotal() }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-secondary">No hay pedidos.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
