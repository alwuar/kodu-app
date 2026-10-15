@props(['status', 'label'])

@php
    $color = match ($status) {
        'paid', 'approved', 'delivered' => 'success',
        'processing', 'shipped', 'contacted' => 'info',
        'new', 'pending' => 'warning',
        'cancelled', 'failed', 'rejected' => 'secondary',
        default => 'light',
    };
@endphp

<span class="badge text-bg-{{ $color }}">{{ $label }}</span>
