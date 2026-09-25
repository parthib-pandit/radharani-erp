@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'icon' => null,
    'iconRight' => null,
    'href' => null,
    'target' => null, // wire:target for the loading spinner; defaults to this button's wire:click
])
@php
$variants = [
    'primary'     => 'gold-sheen text-white border border-gold-dark/30 shadow-gold hover:brightness-[1.07]',
    'secondary'   => 'bg-white text-ink_text-primary border border-line shadow-[0_1px_0_rgba(30,25,15,.04)] hover:border-line-strong hover:bg-surface-sunken',
    'ghost'       => 'bg-transparent text-ink_text-secondary border border-transparent hover:bg-surface-muted hover:text-ink_text-primary',
    'soft'        => 'bg-gold-tint text-gold-dark border border-gold-soft/70 hover:bg-[#F6EAD0]',
    'dark'        => 'bg-ink text-white border border-ink hover:bg-ink-charcoal',
    'danger'      => 'bg-danger text-white border border-danger shadow-[0_6px_16px_-8px_rgba(201,74,74,.7)] hover:bg-[#B64040]',
    'danger-soft' => 'bg-danger-bg text-danger border border-transparent hover:bg-[#F8DCDC]',
];
$sizes = [
    'xs'      => 'h-7 px-2.5 text-[12px] gap-1.5 rounded-md',
    'sm'      => 'h-8 px-3 text-[12.5px] gap-1.5 rounded-lg',
    'md'      => 'h-10 px-4 text-[13px] gap-2 rounded-control',
    'lg'      => 'h-11 px-5 text-[14px] gap-2 rounded-control',
    'icon'    => 'h-9 w-9 rounded-control',
    'icon-sm' => 'h-8 w-8 rounded-lg',
];
$iconSize = ['xs' => 13, 'sm' => 14, 'md' => 15, 'lg' => 16, 'icon' => 16, 'icon-sm' => 15][$size] ?? 15;
// On a link, `target` is the plain HTML attribute (e.g. _blank); on a button it names the wire:target for the spinner.
$linkTarget = $href ? $target : null;
$target = $href ? null : ($target ?: ($attributes->wire('click')->value() ?: null));
$classes = 'press relative inline-flex items-center justify-center font-semibold whitespace-nowrap select-none '
    . 'transition-[background-color,border-color,color,box-shadow,filter] duration-150 '
    . 'disabled:opacity-50 disabled:pointer-events-none aria-disabled:opacity-50 aria-disabled:pointer-events-none '
    . ($sizes[$size] ?? $sizes['md']) . ' ' . ($variants[$variant] ?? $variants['primary']);
$tag = $href ? 'a' : 'button';
$gap = str_contains($sizes[$size] ?? '', 'gap-2') ? 'gap-2' : 'gap-1.5';
@endphp
<{{ $tag }}
    @if($href) href="{{ $href }}" @if($linkTarget) target="{{ $linkTarget }}" @endif @else type="{{ $type }}" @endif
    @if($target) wire:loading.attr="disabled" wire:target="{{ $target }}" @endif
    {{ $attributes->merge(['class' => $classes]) }}>
    <span class="inline-flex items-center justify-center {{ $gap }}" @if($target) wire:loading.class="invisible" wire:target="{{ $target }}" @endif>
        @if($icon)<x-ui.icon :name="$icon" :size="$iconSize" class="shrink-0" />@endif
        @if(trim($slot) !== '')<span>{{ $slot }}</span>@endif
        @if($iconRight)<x-ui.icon :name="$iconRight" :size="$iconSize" class="shrink-0" />@endif
    </span>
    @if($target)
        <span wire:loading.flex wire:target="{{ $target }}" class="absolute inset-0 items-center justify-center">
            <x-ui.icon name="loader" :size="$iconSize + 1" class="animate-spin" />
        </span>
    @endif
</{{ $tag }}>
