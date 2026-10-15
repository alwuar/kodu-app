@extends('layouts.admin')

@section('title', 'Mensajes')

@section('content')
    <div class="admin__head">
        <h1>Mensajes de contacto</h1>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr><th>Nombre</th><th>Empresa</th><th>Contacto</th><th>Fecha</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse ($messages as $message)
                        <tr @class(['fw-semibold' => ! $message->is_read])>
                            <td><a href="{{ route('admin.messages.show', $message) }}">{{ $message->fullName() }}</a></td>
                            <td>{{ $message->company ?: '—' }}</td>
                            <td>{{ $message->email }}<br><small>{{ $message->phone }}</small></td>
                            <td>{{ $message->created_at->timezone('America/Merida')->format('d/m/Y H:i') }}</td>
                            <td>@unless ($message->is_read)<span class="badge text-bg-warning">Nuevo</span>@endunless</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-secondary">No hay mensajes.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $messages->links('pagination::bootstrap-5') }}</div>
@endsection
