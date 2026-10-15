@props([
    'name',
    'label',
    'type' => 'text',
    'placeholder' => '',
    'autocomplete' => null,
    'required' => false,
    'bag' => 'default',
    'prefix' => 'f',
    'useOld' => true,
])

@php
    $id = $prefix.'-'.$name;
    $errorBag = $errors->getBag($bag);
    $error = $errorBag->first($name);
@endphp

<div {{ $attributes->class('field') }}>
    <label class="field__label" for="{{ $id }}">{{ $label }}</label>
    <input id="{{ $id }}" name="{{ $name }}" type="{{ $type }}"
           value="{{ $useOld ? old($name) : '' }}"
           placeholder="{{ $placeholder }}"
           @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif
           @if ($required) required @endif
           @if ($error) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif
           @class(['field__control', 'is-invalid' => $error])>
    @if ($error)
        <p id="{{ $id }}-error" class="field__error">{{ $error }}</p>
    @endif
</div>
