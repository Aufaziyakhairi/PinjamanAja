@props([
    'variant' => 'neutral',
])

@php
    $class = match ($variant) {
        'success' => 'ss-badge-success',
        'warn' => 'ss-badge-warn',
        'danger' => 'ss-badge-danger',
        'info' => 'ss-badge-info',
        default => 'ss-badge-neutral',
    };
@endphp

<span {{ $attributes->merge(['class' => $class]) }}>
    {{ $slot }}
</span>
