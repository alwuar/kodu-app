<x-mail.layout>
    <h1 style="font-size:20px;margin:0 0 12px;">Nuevo mensaje de contacto</h1>
    <p style="margin:0 0 4px;"><strong>Nombre:</strong> {{ $contact->fullName() }}</p>
    <p style="margin:0 0 4px;"><strong>Correo:</strong> {{ $contact->email }}</p>
    <p style="margin:0 0 4px;"><strong>Teléfono:</strong> {{ $contact->phone }}</p>
    <p style="margin:0 0 16px;"><strong>Empresa:</strong> {{ $contact->company ?: '—' }}</p>
    <p style="margin:0;"><a href="{{ route('admin.messages.index') }}" style="color:#e8391c;">Ver mensajes en el panel</a></p>
</x-mail.layout>
