@props(['filters'])

<aside {{ $attributes->class('filters') }} aria-labelledby="filters-title">
    <form class="filters__form" data-filters-form>
        <div class="filters__head">
            <h2 id="filters-title" class="filters__title">Filtrar por producto</h2>
            <button type="button" class="filters__close" data-filters-close aria-label="Cerrar filtros">
                <x-ui.icon name="close" size="22" />
            </button>
        </div>

        @if ($filters['machine'])
            <fieldset class="filters__group">
                <legend class="filters__legend">Maquinaria</legend>
                @foreach ($filters['machine'] as $value => $label)
                    <label class="check">
                        <input type="checkbox" name="machine" value="{{ $value }}" class="check__input">
                        <span class="check__label">{{ $label }}</span>
                    </label>
                @endforeach
            </fieldset>
        @endif

        <fieldset class="filters__group">
            <legend class="filters__legend">Tonelaje</legend>
            <div class="range" data-range data-min="{{ $filters['tons']['min'] }}" data-max="{{ $filters['tons']['max'] }}">
                <div class="range__track" aria-hidden="true"><span class="range__fill" data-range-fill></span></div>
                <label class="visually-hidden" for="tons-min">Tonelaje mínimo</label>
                <input id="tons-min" type="range" name="tons_min" class="range__input"
                       min="{{ $filters['tons']['min'] }}" max="{{ $filters['tons']['max'] }}" value="{{ $filters['tons']['min'] }}" step="1">
                <label class="visually-hidden" for="tons-max">Tonelaje máximo</label>
                <input id="tons-max" type="range" name="tons_max" class="range__input"
                       min="{{ $filters['tons']['min'] }}" max="{{ $filters['tons']['max'] }}" value="{{ $filters['tons']['max'] }}" step="1">
                <output class="range__output" data-range-output>{{ $filters['tons']['min'] }} – {{ $filters['tons']['max'] }} ton</output>
            </div>
        </fieldset>

        @if ($filters['box'])
            <fieldset class="filters__group">
                <legend class="filters__legend">Tipo de caja</legend>
                @foreach ($filters['box'] as $value => $label)
                    <label class="check">
                        <input type="checkbox" name="box" value="{{ $value }}" class="check__input">
                        <span class="check__label">{{ $label }}</span>
                    </label>
                @endforeach
            </fieldset>
        @endif
    </form>
</aside>
