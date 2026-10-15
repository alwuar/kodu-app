@extends('layouts.admin')

@section('title', 'Resumen')

@section('content')
    <div class="admin__head">
        <h1>Resumen</h1>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Nuevo producto</a>
    </div>

    <div class="row g-3 mb-4">
        @foreach ($stats as $stat)
            <div class="col-6 col-md-4 col-xl">
                <a href="{{ $stat['url'] }}" class="stat card card-body">
                    <span class="stat__value">{{ $stat['value'] }}</span>
                    <span class="stat__label">{{ $stat['label'] }}</span>
                </a>
            </div>
        @endforeach
    </div>

    <div class="row g-4">
        <div class="col-xl-7">
            <div class="card">
                <div class="card-header bg-white fw-semibold">Últimos pedidos pagados</div>
                <x-admin.orders-table :orders="$orders" />
            </div>
        </div>
        <div class="col-xl-5">
            <div class="card">
                <div class="card-header bg-white fw-semibold">Solicitudes de distribuidor</div>
                <div class="list-group list-group-flush">
                    @forelse ($dealers as $dealer)
                        <a href="{{ route('admin.dealers.show', $dealer) }}" class="d-flex justify-content-between px-3 py-2 border-bottom text-body">
                            <span>{{ $dealer->fullName() }} <small class="text-secondary">· {{ $dealer->city }}</small></span>
                            <x-admin.status :status="$dealer->status" :label="$dealer->statusLabel()" />
                        </a>
                    @empty
                        <p class="text-secondary p-3 mb-0">Todavía no hay solicitudes.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
