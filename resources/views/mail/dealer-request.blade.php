<x-mail.layout>
    <h1 style="font-size:20px;margin:0 0 12px;">Nueva solicitud de distribuidor</h1>
    <p style="margin:0 0 4px;"><strong>Nombre:</strong> {{ $dealer->fullName() }}</p>
    <p style="margin:0 0 4px;"><strong>Ciudad:</strong> {{ $dealer->city }}</p>
    <p style="margin:0 0 4px;"><strong>Teléfono:</strong> {{ $dealer->phone }}</p>
    <p style="margin:0 0 4px;"><strong>Correo:</strong> {{ $dealer->email }}</p>
    <p style="margin:0 0 16px;"><strong>Empresa:</strong> {{ $dealer->company ?: '—' }}</p>
    <p style="margin:0;"><a href="{{ route('admin.dealers.index') }}" style="color:#e8391c;">Ver solicitudes en el panel</a></p>
</x-mail.layout>
