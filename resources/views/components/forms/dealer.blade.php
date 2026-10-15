@props(['origin' => 'pagina', 'prefix' => 'dp'])

@php
    // Solo rellena/valida este formulario si fue el que se envió
    $isMine = old('origen') === $origin;
    $sent = session('dealer_origin') === $origin ? session('dealer_status') : null;
@endphp

<form method="POST" action="{{ route('dealers.store') }}" {{ $attributes->class('contact-form dealer-form') }} novalidate>
    @csrf
    <input type="hidden" name="origen" value="{{ $origin }}">

    @if ($sent)
        <p role="status" class="flash dealer-form__flash">{{ $sent }}</p>
    @endif

    <div class="contact-form__grid">
        <x-forms.field :prefix="$prefix" bag="dealer" :use-old="$isMine" name="nombre" label="Nombre" placeholder="Cecilia" autocomplete="given-name" required />
        <x-forms.field :prefix="$prefix" bag="dealer" :use-old="$isMine" name="apellido" label="Apellido" placeholder="Acevedo" autocomplete="family-name" required />
        <x-forms.field :prefix="$prefix" bag="dealer" :use-old="$isMine" name="ciudad" label="Ciudad" placeholder="Mérida, Yucatán" autocomplete="address-level2" required />
        <x-forms.field :prefix="$prefix" bag="dealer" :use-old="$isMine" name="telefono" label="Teléfono" type="tel" placeholder="999 123 4567" autocomplete="tel" required />
        <x-forms.field :prefix="$prefix" bag="dealer" :use-old="$isMine" name="email" label="Correo electrónico" type="email" placeholder="cecy@example.com" autocomplete="email" required />
        <x-forms.field :prefix="$prefix" bag="dealer" :use-old="$isMine" name="empresa" label="Empresa" placeholder="Nombre de tu empresa" autocomplete="organization" />
    </div>

    <x-ui.button type="submit" size="md" class="contact-form__submit">Enviar solicitud</x-ui.button>
</form>
