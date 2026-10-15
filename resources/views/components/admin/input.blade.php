@props(['name', 'label', 'value' => null, 'type' => 'text', 'help' => null, 'required' => false])

@php $id = 'in-'.str_replace(['[', ']', '.'], '-', $name); @endphp

<div {{ $attributes->only('class')->merge(['class' => 'mb-3']) }}>
    <label for="{{ $id }}" class="form-label">{{ $label }} @if ($required)<span class="text-danger">*</span>@endif</label>
    @if ($type === 'textarea')
        <textarea id="{{ $id }}" name="{{ $name }}" rows="3" {{ $attributes->except('class') }}
                  @class(['form-control', 'is-invalid' => $errors->has($name)])>{{ old($name, $value) }}</textarea>
    @else
        <input id="{{ $id }}" type="{{ $type }}" name="{{ $name }}" value="{{ old($name, $value) }}" @if ($required) required @endif
               {{ $attributes->except('class') }}
               @class(['form-control', 'is-invalid' => $errors->has($name)])>
    @endif
    @error($name) <div class="invalid-feedback">{{ $message }}</div> @enderror
    @if ($help) <div class="form-text">{{ $help }}</div> @endif
</div>
