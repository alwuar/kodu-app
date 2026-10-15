@extends('layouts.admin')

@section('title', 'Distribuidores')

@section('content')
    <div class="admin__head">
        <h1>Solicitudes de distribuidor</h1>
    </div>

    <ul class="nav nav-pills mb-3 gap-1">
        <li class="nav-item"><a href="{{ route('admin.dealers.index') }}" @class(['nav-link', 'active' => ! $status])>Todas</a></li>
        @foreach (\App\Models\DealerRequest::STATUSES as $key => $label)
            <li class="nav-item"><a href="{{ route('admin.dealers.index', ['estado' => $key]) }}" @class(['nav-link', 'active' => $status === $key])>{{ $label }}</a></li>
        @endforeach
    </ul>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr><th>Nombre</th><th>Ciudad</th><th>Empresa</th><th>Contacto</th><th>Fecha</th><th>Estado</th></tr>
                </thead>
                <tbody>
                    @forelse ($dealers as $dealer)
                        <tr>
                            <td><a href="{{ route('admin.dealers.show', $dealer) }}" class="fw-semibold">{{ $dealer->fullName() }}</a></td>
                            <td>{{ $dealer->city }}</td>
                            <td>{{ $dealer->company ?: '—' }}</td>
                            <td><a href="mailto:{{ $dealer->email }}">{{ $dealer->email }}</a><br><small>{{ $dealer->phone }}</small></td>
                            <td>{{ $dealer->created_at->timezone('America/Merida')->format('d/m/Y H:i') }}</td>
                            <td><x-admin.status :status="$dealer->status" :label="$dealer->statusLabel()" /></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-secondary">No hay solicitudes.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $dealers->links('pagination::bootstrap-5') }}</div>
@endsection
