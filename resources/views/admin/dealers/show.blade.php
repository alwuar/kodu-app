@extends('layouts.admin')

@section('title', $dealer->fullName())

@section('content')
    <div class="admin__head">
        <h1>{{ $dealer->fullName() }} <x-admin.status :status="$dealer->status" :label="$dealer->statusLabel()" /></h1>
        <a href="{{ route('admin.dealers.index') }}" class="btn btn-outline-secondary">Volver</a>
    </div>

    <div class="row g-4">
        <div class="col-xl-7">
            <div class="card card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Ciudad</dt><dd class="col-sm-8">{{ $dealer->city }}</dd>
                    <dt class="col-sm-4">Teléfono</dt><dd class="col-sm-8"><a href="tel:{{ $dealer->phone }}">{{ $dealer->phone }}</a></dd>
                    <dt class="col-sm-4">Correo</dt><dd class="col-sm-8"><a href="mailto:{{ $dealer->email }}">{{ $dealer->email }}</a></dd>
                    <dt class="col-sm-4">Empresa</dt><dd class="col-sm-8">{{ $dealer->company ?: '—' }}</dd>
                    <dt class="col-sm-4">Origen</dt><dd class="col-sm-8">{{ $dealer->source === 'modal' ? 'Formulario emergente' : 'Página de distribuidores' }}</dd>
                    <dt class="col-sm-4">Recibida</dt><dd class="col-sm-8 mb-0">{{ $dealer->created_at->timezone('America/Merida')->format('d/m/Y H:i') }}</dd>
                </dl>
            </div>
        </div>
        <div class="col-xl-5">
            <form method="POST" action="{{ route('admin.dealers.update', $dealer) }}" class="card card-body">
                @csrf
                @method('PATCH')
                <div class="mb-3">
                    <label for="status" class="form-label">Estado</label>
                    <select id="status" name="status" class="form-select">
                        @foreach (\App\Models\DealerRequest::STATUSES as $key => $label)
                            <option value="{{ $key }}" @selected($dealer->status === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <x-admin.input name="notes" label="Notas internas" type="textarea" :value="$dealer->notes" />
                <button type="submit" class="btn btn-primary">Guardar</button>
            </form>
            <form method="POST" action="{{ route('admin.dealers.destroy', $dealer) }}" class="mt-3" data-confirm="¿Borrar esta solicitud?">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm">Borrar solicitud</button>
            </form>
        </div>
    </div>
@endsection
