<!DOCTYPE html>
<html lang="es-MX">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Panel') · {{ config('kodu.name') }}</title>
    <link rel="icon" href="{{ asset('images/favicon.svg') }}" type="image/svg+xml">
    @vite(['resources/scss/admin.scss', 'resources/js/admin.js'])
</head>
<body>
@auth
    @php
        $nav = [
            ['label' => 'Resumen', 'route' => 'admin.dashboard', 'match' => 'admin.dashboard'],
            ['label' => 'Pedidos', 'route' => 'admin.orders.index', 'match' => 'admin.orders.*'],
            ['label' => 'Productos', 'route' => 'admin.products.index', 'match' => 'admin.products.*'],
            ['label' => 'Categorías', 'route' => 'admin.categories.index', 'match' => 'admin.categories.*'],
            ['label' => 'Distribuidores', 'route' => 'admin.dealers.index', 'match' => 'admin.dealers.*', 'badge' => \App\Models\DealerRequest::query()->where('status', 'new')->count()],
            ['label' => 'Mensajes', 'route' => 'admin.messages.index', 'match' => 'admin.messages.*', 'badge' => \App\Models\ContactMessage::query()->where('is_read', false)->count()],
            ['label' => 'Banner del inicio', 'route' => 'admin.home.edit', 'match' => 'admin.home.*'],
        ];
    @endphp
    <div class="admin">
        <aside class="admin__side">
            <a href="{{ route('admin.dashboard') }}" class="admin__brand d-inline-block">
                <img src="{{ asset(config('kodu.logo')) }}" alt="KODU">
            </a>
            <nav class="admin__nav nav flex-column" aria-label="Panel">
                @foreach ($nav as $item)
                    <a href="{{ route($item['route']) }}" @class(['nav-link', 'active' => request()->routeIs($item['match'])])>
                        {{ $item['label'] }}
                        @if (! empty($item['badge']))
                            <span class="badge rounded-pill text-bg-light">{{ $item['badge'] }}</span>
                        @endif
                    </a>
                @endforeach
                <hr class="border-secondary my-3 d-none d-lg-block">
                <a href="{{ route('home') }}" class="nav-link" target="_blank" rel="noopener">Ver sitio ↗</a>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="nav-link btn btn-link w-100 text-start">Cerrar sesión</button>
                </form>
            </nav>
        </aside>

        <main class="admin__main">
            @if (session('status'))
                <div class="alert alert-success" role="status">{{ session('status') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">Revisa los campos marcados.</div>
            @endif

            @yield('content')
        </main>
    </div>
@else
    @yield('content')
@endauth
</body>
</html>
