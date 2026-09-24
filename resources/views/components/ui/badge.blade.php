@props(['tone' => 'neutral', 'size' => 'md', 'dot' => false])
@php
$tones = [
    'success' => 'bg-success-bg text-success ring-success/15',
    'warning' => 'bg-warning-bg text-warning ring-warning/15',
    'danger'  => 'bg-danger-bg text-danger ring-danger/15',
    'info'    => 'bg-info-bg text-info ring-info/15',
    'gold'    => 'bg-gold-tint text-gold-dark ring-gold/20',
    'dark'    => 'bg-ink text-gold-light ring-ink',
    'neutral' => 'bg-surface-muted text-ink_text-secondary ring-line',
];
$sizes = [
    'sm' => 'h-5 px-2 text-[11px] gap-1',
    'md' => 'h-6 px-2.5 text-[12px] gap-1.5',
    'lg' => 'h-7 px-3 text-[12.5px] gap-1.5',
];
$classes = ($tones[$tone] ?? $tones['neutral']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp
<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full font-semibold whitespace-nowrap ring-1 ring-inset $classes"]) }}>
    @if ($dot)<span class="w-1.5 h-1.5 rounded-full bg-current opacity-80"></span>@endif
    {{ $slot }}
</span>
