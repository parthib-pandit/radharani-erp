@props(['variant' => 'primary', 'type' => 'button', 'icon' => null])
@php
$variants = [
    'primary'   => 'bg-gold text-white hover:bg-gold-dark',
    'secondary' => 'bg-white text-ink_text-primary border border-line hover:bg-surface-muted',
    'ghost'     => 'bg-transparent text-ink_text-secondary hover:bg-surface-muted',
    'danger'    => 'bg-danger text-white hover:opacity-90',
];
$classes = $variants[$variant] ?? $variants['primary'];
@endphp
<button type="{{ $type }}" {{ $attributes->merge(['class' => "inline-flex items-center justify-center gap-2 h-10 px-4 rounded-lg text-[13px] font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed $classes"]) }}>
    @if($icon)
        <x-ui.icon :name="$icon" :size="14" />
    @endif
    {{ $slot }}
</button>
