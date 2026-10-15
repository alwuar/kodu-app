@extends('layouts.admin')

@section('title', $message->fullName())

@section('content')
    <div class="admin__head">
        <h1>{{ $message->fullName() }}</h1>
        <a href="{{ route('admin.messages.index') }}" class="btn btn-outline-secondary">Volver</a>
    </div>

    <div class="card card-body" style="max-width:640px">
        <dl class="row mb-0">
            <dt class="col-sm-4">Correo</dt><dd class="col-sm-8"><a href="mailto:{{ $message->email }}">{{ $message->email }}</a></dd>
            <dt class="col-sm-4">Teléfono</dt><dd class="col-sm-8"><a href="tel:{{ $message->phone }}">{{ $message->phone }}</a></dd>
            <dt class="col-sm-4">Empresa</dt><dd class="col-sm-8">{{ $message->company ?: '—' }}</dd>
            <dt class="col-sm-4">Recibido</dt><dd class="col-sm-8 mb-0">{{ $message->created_at->timezone('America/Merida')->format('d/m/Y H:i') }}</dd>
        </dl>
    </div>

    <form method="POST" action="{{ route('admin.messages.destroy', $message) }}" class="mt-3" data-confirm="¿Borrar este mensaje?">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-outline-danger btn-sm">Borrar mensaje</button>
    </form>
@endsection
