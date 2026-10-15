@extends('layouts.admin')

@section('title', 'Banner del inicio')

@section('content')
    <div class="admin__head">
        <h1>Banner del inicio</h1>
        <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn btn-outline-secondary">Ver inicio ↗</a>
    </div>

    <form method="POST" action="{{ route('admin.home.update') }}" enctype="multipart/form-data" class="row g-4">
        @csrf
        @method('PUT')

        <div class="col-xl-7">
            <div class="card card-body">
                <h2 class="h6">Video de fondo</h2>
                <video id="video-preview" class="preview-video mb-3" muted loop autoplay playsinline
                       @if ($video) src="{{ asset('storage/'.$video) }}" @else hidden @endif></video>
                @unless ($video)
                    <p class="text-secondary">No hay video: el banner se muestra con el fondo oscuro del diseño.</p>
                @endunless

                <label for="in-video" class="form-label">{{ $video ? 'Cambiar video' : 'Subir video' }}</label>
                <input id="in-video" type="file" name="video" accept="video/mp4,video/webm" data-preview="#video-preview"
                       @class(['form-control', 'is-invalid' => $errors->has('video')])>
                @error('video') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <div class="form-text">
                    MP4 (H.264) o WebM, horizontal 1920 × 1080, sin audio, de 10 a 20 segundos.
                    Para que el sitio cargue rápido procura que pese menos de 10 MB (máximo 50 MB).
                    Revisa también que tu hosting permita subir archivos de ese tamaño.
                </div>
                @if ($video)
                    <div class="form-check mt-2">
                        <input id="remove_video" type="checkbox" name="remove_video" value="1" class="form-check-input">
                        <label for="remove_video" class="form-check-label">Quitar video</label>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-xl-5">
            <div class="card card-body">
                <h2 class="h6">Imagen de carga (opcional)</h2>
                <p class="form-text mt-0">Se ve mientras carga el video y en celulares con ahorro de datos. Usa un cuadro del mismo video.</p>
                <img id="poster-preview" src="{{ $poster ? asset('storage/'.$poster) : '' }}" alt="" class="img-fluid rounded mb-3" @unless ($poster) hidden @endunless>
                <input type="file" name="poster" accept="image/jpeg,image/png,image/webp" data-preview="#poster-preview" aria-label="Imagen de carga"
                       @class(['form-control', 'is-invalid' => $errors->has('poster')])>
                @error('poster') <div class="invalid-feedback">{{ $message }}</div> @enderror
                @if ($poster)
                    <div class="form-check mt-2">
                        <input id="remove_poster" type="checkbox" name="remove_poster" value="1" class="form-check-input">
                        <label for="remove_poster" class="form-check-label">Quitar imagen</label>
                    </div>
                @endif
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-4">Guardar</button>
        </div>
    </form>
@endsection
