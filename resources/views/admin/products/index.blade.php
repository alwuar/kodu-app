@extends('layouts.admin')

@section('title', 'Productos')

@section('content')
    <div class="admin__head">
        <h1>Productos</h1>
        <a href="{{ route('admin.products.create', array_filter(['categoria' => request('categoria')])) }}" class="btn btn-primary">Nuevo producto</a>
    </div>

    <form class="row g-2 mb-3" method="GET">
        <div class="col-sm-5 col-lg-4">
            <select name="categoria" class="form-select" onchange="this.form.submit()" aria-label="Filtrar por categoría">
                <option value="">Todas las categorías</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected((string) request('categoria') === (string) $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-5 col-lg-4">
            <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Buscar por nombre o SKU">
        </div>
        <div class="col-auto"><button class="btn btn-outline-secondary">Buscar</button></div>
    </form>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th></th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th class="text-end">Precio</th>
                        <th>Ficha PDF</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>
                                @if ($product->imagePath() && is_file(public_path($product->imagePath())))
                                    <img src="{{ asset($product->imagePath()) }}" alt="" class="thumb">
                                @else
                                    <span class="thumb d-inline-block"></span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.products.edit', $product) }}" class="fw-semibold">{{ $product->name }}</a>
                                @if ($product->sku)<br><small class="text-secondary">{{ $product->sku }}</small>@endif
                            </td>
                            <td>{{ $product->category->name }}</td>
                            <td class="text-end text-nowrap">
                                @if ($product->price > 0)
                                    {{ $product->formattedPrice() }}
                                @else
                                    <span class="text-danger">Sin precio</span>
                                @endif
                            </td>
                            <td>{{ $product->datasheetUrl() ? 'Sí' : '—' }}</td>
                            <td>
                                @if ($product->is_active)
                                    <span class="badge text-bg-success">Visible</span>
                                @else
                                    <span class="badge text-bg-secondary">Oculto</span>
                                @endif
                            </td>
                            <td class="text-end text-nowrap">
                                <a href="{{ $product->url() }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary">Ver</a>
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-secondary">No hay productos.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $products->links('pagination::bootstrap-5') }}</div>
@endsection
