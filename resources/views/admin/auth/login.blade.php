@extends('layouts.admin')

@section('title', 'Entrar')

@section('content')
    <div class="login">
        <div class="card shadow">
            <div class="card-body p-4">
                <img src="{{ asset(config('kodu.logo')) }}" alt="KODU" width="130" class="mb-4">
                <h1 class="h5 mb-3">Panel de administración</h1>
                <form method="POST" action="{{ route('admin.login.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                               @class(['form-control', 'is-invalid' => $errors->has('email')])>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input id="password" type="password" name="password" required autocomplete="current-password" class="form-control">
                    </div>
                    <div class="form-check mb-3">
                        <input id="remember" type="checkbox" name="remember" value="1" class="form-check-input">
                        <label for="remember" class="form-check-label">Mantener la sesión</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Entrar</button>
                </form>
            </div>
        </div>
    </div>
@endsection
