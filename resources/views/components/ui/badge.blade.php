@props(['tone' => 'neutral'])
@php
$tones = [
    'success' => 'bg-success-bg text-success',
    'warning' => 'bg-warning-bg text-warning',
    'danger'  => 'bg-danger-bg text-danger',
    'info'    => 'bg-info-bg text-info',
    'gold'    => 'bg-gold-soft/40 text-gold-dark',
    'neutral' => 'bg-surface-muted text-ink_text-secondary',
];
$classes = $tones[$tone] ?? $tones['neutral'];
@endphp
<span {{ $attributes->merge(['class' => "inline-flex items-center h-6 px-2.5 rounded-full text-xs font-medium whitespace-nowrap $classes"]) }}>
    {{ $slot }}
</span>
