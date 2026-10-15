@php $useOld = old('origen') === null; @endphp

<form method="POST" action="{{ route('contact.store') }}" {{ $attributes->class('contact-form') }} novalidate>
    @csrf

    <x-ui.flash class="contact-form__flash" />

    <div class="contact-form__grid">
        <x-forms.field name="nombre" label="Nombre" placeholder="Cecilia" autocomplete="given-name" required :use-old="$useOld" />
        <x-forms.field name="apellido" label="Apellido" placeholder="Acevedo" autocomplete="family-name" required :use-old="$useOld" />
        <x-forms.field name="email" label="Email" type="email" placeholder="cecy@example.com" autocomplete="email" required :use-old="$useOld" />

        <div class="field">
            <label class="field__label" for="f-telefono">Teléfono</label>
            <div @class(['field__control', 'field__phone', 'is-invalid' => $errors->has('telefono')])>
                <label class="visually-hidden" for="f-pais">País</label>
                <select id="f-pais" name="pais" class="field__select" autocomplete="tel-country-code">
                    @foreach (config('kodu.phone_countries') as $country)
                        <option value="{{ $country['code'] }}" @selected(old('pais', 'MX') === $country['code'])>{{ $country['code'] }}</option>
                    @endforeach
                </select>
                <x-ui.icon name="chevron-down" size="14" class="field__chevron" />
                <input id="f-telefono" name="telefono" type="tel" value="{{ $useOld ? old('telefono') : '' }}"
                       placeholder="+52 (555) 987-6543" autocomplete="tel-national" required
                       @error('telefono') aria-invalid="true" aria-describedby="e-telefono" @enderror
                       class="field__phone-input">
            </div>
            @error('telefono')
                <p id="e-telefono" class="field__error">{{ $message }}</p>
            @enderror
        </div>

        <x-forms.field name="empresa" label="Empresa" placeholder="Nombre de tu empresa" autocomplete="organization" :use-old="$useOld" />
    </div>

    <x-ui.button type="submit" size="md" class="contact-form__submit">Enviar</x-ui.button>
</form>
