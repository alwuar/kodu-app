@extends('layouts.admin')

@section('title', $category->exists ? 'Editar categoría' : 'Nueva categoría')

@section('content')
    <div class="admin__head">
        <h1>{{ $category->exists ? 'Editar: '.$category->name : 'Nueva categoría' }}</h1>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Volver</a>
    </div>

    <form method="POST" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
        @csrf
        @if ($category->exists) @method('PUT') @endif

        <div class="row g-4">
            <div class="col-xl-8">
                <div class="card card-body">
                    <div class="row">
                        <x-admin.input class="col-md-6" name="name" label="Nombre" :value="$category->name" required data-slug-source="#in-slug" help="Así aparece en el menú y como título de la página." />
                        <x-admin.input class="col-md-6" name="singular" label="Nombre en singular" :value="$category->singular" help="Ej. “Martillo hidráulico”. Se usa en la ficha del producto." />
                    </div>
                    <x-admin.input name="slug" label="URL" :value="$category->slug" help="Se arma sola con el nombre. Ej.: /martillos-hidraulicos" />
                    <x-admin.input name="description" label="Descripción" type="textarea" :value="$category->description" help="Texto debajo del título en el listado." />
                </div>

                <div class="card card-body mt-4">
                    <h2 class="h6 mb-3">SEO (Google)</h2>
                    <x-admin.input name="seo_title" label="Título" :value="$category->seo_title" maxlength="70" help="Máximo 70 caracteres. Si lo dejas vacío se usa el nombre." />
                    <x-admin.input name="seo_description" label="Descripción" type="textarea" :value="$category->seo_description" maxlength="170" help="Máximo 170 caracteres." />
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card card-body">
                    <x-admin.switch name="is_active" label="Visible en el sitio" :checked="$category->is_active" />
                    <x-admin.switch name="show_in_menu" label="Mostrar en el menú" :checked="$category->show_in_menu" />
                    <x-admin.input name="sort" label="Orden" type="number" min="0" :value="$category->sort" help="Menor número = aparece primero. La primera categoría es la del carrusel del inicio." />
                    <button type="submit" class="btn btn-primary w-100">Guardar</button>
                </div>
            </div>
        </div>
    </form>
@endsection
