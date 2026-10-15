@extends('layouts.admin')

@section('title', $product->exists ? 'Editar producto' : 'Nuevo producto')

@php
    use App\Support\Money;
    $specs = old('specs', $product->specs ?? []);
    $imagePath = $product->imagePath();
    $hasImage = $imagePath && is_file(public_path($imagePath));
@endphp

@section('content')
    <div class="admin__head">
        <h1>{{ $product->exists ? 'Editar: '.$product->name : 'Nuevo producto' }}</h1>
        <div class="d-flex gap-2">
            @if ($product->exists)
                <a href="{{ $product->url() }}" target="_blank" rel="noopener" class="btn btn-outline-secondary">Ver en el sitio ↗</a>
            @endif
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Volver</a>
        </div>
    </div>

    <form method="POST" enctype="multipart/form-data"
          action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}">
        @csrf
        @if ($product->exists) @method('PUT') @endif

        <div class="row g-4">
            <div class="col-xl-8">
                <div class="card card-body">
                    <div class="row">
                        <x-admin.input class="col-md-6" name="name" label="Nombre" :value="$product->name" required data-slug-source="#in-slug" />
                        <x-admin.input class="col-md-6" name="sku" label="SKU / modelo" :value="$product->sku" />
                    </div>
                    <x-admin.input name="slug" label="URL" :value="$product->slug" help="Se arma sola con el nombre." />
                    <x-admin.input name="kicker" label="Texto rojo arriba del nombre" :value="$product->kicker"
                                   help="Ej. “Martillo hidráulico · Caja cerrada”. Si lo dejas vacío se arma con la categoría y el tipo de caja." />
                    <x-admin.input name="summary" label="Descripción corta" type="textarea" :value="$product->summary" />
                </div>

                <div class="card card-body mt-4">
                    <h2 class="h6">Filtros y etiquetas</h2>
                    <p class="form-text mt-0">Con esto se arman las etiquetas rojas de la tarjeta y los filtros del listado.</p>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="in-machine" class="form-label">Maquinaria</label>
                            <select id="in-machine" name="machine" @class(['form-select', 'is-invalid' => $errors->has('machine')])>
                                <option value="">—</option>
                                @foreach (config('catalog.machines') as $value => $label)
                                    <option value="{{ $value }}" @selected(old('machine', $product->machine) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <x-admin.input class="col-6 col-md-2" name="tons_min" label="Ton. mín." type="number" min="0" :value="$product->tons_min" />
                        <x-admin.input class="col-6 col-md-2" name="tons_max" label="Ton. máx." type="number" min="0" :value="$product->tons_max" />
                        <div class="col-md-4 mb-3">
                            <label for="in-box" class="form-label">Tipo de caja</label>
                            <select id="in-box" name="box" @class(['form-select', 'is-invalid' => $errors->has('box')])>
                                <option value="">— (no aplica)</option>
                                @foreach (config('catalog.boxes') as $value => $label)
                                    <option value="{{ $value }}" @selected(old('box', $product->box) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @error('tons_max') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="card card-body mt-4" data-specs>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="h6 mb-0">Ficha técnica (tabla)</h2>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-spec-add>+ Agregar dato</button>
                    </div>
                    <div data-specs-list>
                        @foreach ($specs as $i => $row)
                            <div class="spec-row input-group mb-2" data-spec-row>
                                <input type="text" name="specs[{{ $i }}][label]" value="{{ $row['label'] ?? '' }}" placeholder="Dato (ej. Peso operativo)"
                                       aria-label="Dato" @class(['form-control', 'is-invalid' => $errors->has("specs.$i.label")])>
                                <input type="text" name="specs[{{ $i }}][value]" value="{{ $row['value'] ?? '' }}" placeholder="Valor (ej. 1,250 kg)"
                                       aria-label="Valor" @class(['form-control', 'is-invalid' => $errors->has("specs.$i.value")])>
                                <button type="button" class="btn btn-outline-secondary" data-spec-up title="Subir">↑</button>
                                <button type="button" class="btn btn-outline-danger" data-spec-remove title="Quitar">×</button>
                            </div>
                        @endforeach
                    </div>
                    <template>
                        <div class="spec-row input-group mb-2" data-spec-row>
                            <input type="text" name="specs[0][label]" placeholder="Dato (ej. Peso operativo)" aria-label="Dato" class="form-control">
                            <input type="text" name="specs[0][value]" placeholder="Valor (ej. 1,250 kg)" aria-label="Valor" class="form-control">
                            <button type="button" class="btn btn-outline-secondary" data-spec-up title="Subir">↑</button>
                            <button type="button" class="btn btn-outline-danger" data-spec-remove title="Quitar">×</button>
                        </div>
                    </template>
                    <p class="form-text mb-0">Los renglones vacíos se ignoran.</p>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card card-body">
                    <div class="mb-3">
                        <label for="in-category" class="form-label">Categoría <span class="text-danger">*</span></label>
                        <select id="in-category" name="category_id" required @class(['form-select', 'is-invalid' => $errors->has('category_id')])>
                            <option value="">Elige una…</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected((string) old('category_id', $product->category_id) === (string) $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="in-price" class="form-label">Precio (MXN, IVA incluido) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input id="in-price" type="text" inputmode="decimal" name="price" required
                                   value="{{ old('price', $product->exists ? Money::toInput($product->price) : '') }}"
                                   @class(['form-control', 'is-invalid' => $errors->has('price') || $errors->has('price_cents')])>
                        </div>
                        @error('price_cents') <div class="text-danger small">{{ $message }}</div> @enderror
                        <div class="form-text">Solo se muestra en el carrito. Con precio 0 aparece “Solicitar cotización”.</div>
                    </div>

                    <x-admin.switch name="is_active" label="Visible en el sitio" :checked="$product->is_active" />
                    <x-admin.input name="sort" label="Orden" type="number" min="0" :value="$product->sort ?? 0" help="Menor número = aparece primero." />
                    <button type="submit" class="btn btn-primary w-100">Guardar</button>
                </div>

                <div class="card card-body mt-4">
                    <h2 class="h6">Imagen</h2>
                    <img id="image-preview" src="{{ $hasImage ? asset($imagePath) : '' }}" alt="" class="img-fluid mb-3 bg-light" style="max-height:220px;object-fit:contain" @unless ($hasImage) hidden @endunless>
                    <input type="file" name="image" accept="image/jpeg,image/png,image/webp" data-preview="#image-preview"
                           @class(['form-control', 'is-invalid' => $errors->has('image')]) aria-label="Imagen">
                    @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div class="form-text">PNG o WebP con fondo transparente, máximo 4 MB. Ideal: 480 × 1200 px.</div>
                    @if ($hasImage)
                        <div class="form-check mt-2">
                            <input id="remove_image" type="checkbox" name="remove_image" value="1" class="form-check-input">
                            <label for="remove_image" class="form-check-label">Quitar imagen</label>
                        </div>
                    @endif
                </div>

                <div class="card card-body mt-4">
                    <h2 class="h6">Ficha técnica en PDF</h2>
                    @if ($product->datasheetUrl())
                        <p class="mb-2"><a href="{{ $product->datasheetUrl() }}" target="_blank" rel="noopener">Ver PDF actual ↗</a></p>
                    @endif
                    <input type="file" name="datasheet" accept="application/pdf"
                           @class(['form-control', 'is-invalid' => $errors->has('datasheet')]) aria-label="Ficha técnica PDF">
                    @error('datasheet') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div class="form-text">Máximo 20 MB. Aparece como “Descargar ficha técnica (PDF)” en el producto.</div>
                    @if ($product->datasheetUrl())
                        <div class="form-check mt-2">
                            <input id="remove_datasheet" type="checkbox" name="remove_datasheet" value="1" class="form-check-input">
                            <label for="remove_datasheet" class="form-check-label">Quitar PDF</label>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </form>

    @if ($product->exists)
        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="mt-4" data-confirm="¿Borrar {{ $product->name }}? No se puede deshacer.">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger">Borrar producto</button>
        </form>
    @endif
@endsection
