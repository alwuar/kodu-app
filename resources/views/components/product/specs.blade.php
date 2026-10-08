@props(['specs' => []])

<section {{ $attributes->class('specs') }} aria-labelledby="specs-title">
    <h2 id="specs-title" class="h-block specs__title">Ficha técnica</h2>
    <dl class="specs__table">
        @foreach ($specs as $label => $value)
            <div class="specs__row">
                <dt>{{ $label }}</dt>
                <dd>{{ $value }}</dd>
            </div>
        @endforeach
    </dl>
</section>
