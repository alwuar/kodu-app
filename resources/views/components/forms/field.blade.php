@props(['name', 'label', 'type' => 'text', 'placeholder' => '', 'autocomplete' => null, 'required' => false])

<div {{ $attributes->class('field') }}>
    <label class="field__label" for="f-{{ $name }}">{{ $label }}</label>
    <input id="f-{{ $name }}" name="{{ $name }}" type="{{ $type }}"
           value="{{ old($name) }}"
           placeholder="{{ $placeholder }}"
           @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif
           @if ($required) required @endif
           @error($name) aria-invalid="true" aria-describedby="e-{{ $name }}" @enderror
           @class(['field__control', 'is-invalid' => $errors->has($name)])>
    @error($name)
        <p id="e-{{ $name }}" class="field__error">{{ $message }}</p>
    @enderror
</div>
