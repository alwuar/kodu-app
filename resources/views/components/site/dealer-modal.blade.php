@php
    // Se abre sola al regresar con errores o con el mensaje de enviado
    $open = (old('origen') === 'modal' && $errors->dealer->any()) || session('dealer_origin') === 'modal';
@endphp

<dialog class="modal" id="dealer-modal" aria-labelledby="dealer-modal-title" data-dealer-modal @if ($open) data-open @endif>
    <div class="modal__panel">
        <button type="button" class="modal__close" data-dealer-close aria-label="Cerrar">
            <x-ui.icon name="close" size="22" />
        </button>

        <p class="modal__kicker">{{ config('kodu.dealer_cta.kicker') }}</p>
        <h2 id="dealer-modal-title" class="modal__title">¿Quieres ser distribuidor?</h2>
        <p class="modal__text">{{ config('kodu.dealer_cta.text') }}</p>

        <x-forms.dealer origin="modal" prefix="dm" class="modal__form" />
    </div>
</dialog>
