@extends('layouts.admin')

@section('title', 'Categorías')

@section('content')
    <div class="admin__head">
        <h1>Categorías</h1>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Nueva categoría</a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Orden</th>
                        <th>Nombre</th>
                        <th>URL</th>
                        <th>Productos</th>
                        <th>Menú</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td>{{ $category->sort }}</td>
                            <td><a href="{{ route('admin.categories.edit', $category) }}" class="fw-semibold">{{ $category->name }}</a></td>
                            <td><a href="{{ $category->url() }}" target="_blank" rel="noopener" class="text-secondary">/{{ $category->slug }}</a></td>
                            <td><a href="{{ route('admin.products.index', ['categoria' => $category->id]) }}">{{ $category->products_count }}</a></td>
                            <td>{{ $category->show_in_menu ? 'Sí' : 'No' }}</td>
                            <td>
                                @if ($category->is_active)
                                    <span class="badge text-bg-success">Visible</span>
                                @else
                                    <span class="badge text-bg-secondary">Oculta</span>
                                @endif
                            </td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="d-inline" data-confirm="¿Borrar la categoría {{ $category->name }}?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Borrar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-secondary">No hay categorías.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
