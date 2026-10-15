@props(['name', 'label', 'checked' => false, 'help' => null])

<div class="form-check form-switch mb-3">
    <input type="hidden" name="{{ $name }}" value="0">
    <input id="sw-{{ $name }}" type="checkbox" role="switch" name="{{ $name }}" value="1" class="form-check-input" @checked(old($name, $checked))>
    <label for="sw-{{ $name }}" class="form-check-label">{{ $label }}</label>
    @if ($help) <div class="form-text">{{ $help }}</div> @endif
</div>
