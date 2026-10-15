@extends('layouts.admin')

@section('title', 'Pedidos')

@section('content')
    <div class="admin__head">
        <h1>Pedidos</h1>
    </div>

    <ul class="nav nav-pills mb-3 gap-1">
        <li class="nav-item"><a href="{{ route('admin.orders.index') }}" @class(['nav-link', 'active' => ! $status])>Todos</a></li>
        @foreach (\App\Models\Order::STATUSES as $key => $label)
            <li class="nav-item"><a href="{{ route('admin.orders.index', ['estado' => $key]) }}" @class(['nav-link', 'active' => $status === $key])>{{ $label }}</a></li>
        @endforeach
    </ul>
    <p class="form-text mt-0">“Todos” no incluye los pendientes de pago (carritos que se mandaron a Stripe y no se pagaron).</p>

    <div class="card">
        <x-admin.orders-table :orders="$orders" />
    </div>

    <div class="mt-3">{{ $orders->links('pagination::bootstrap-5') }}</div>
@endsection
