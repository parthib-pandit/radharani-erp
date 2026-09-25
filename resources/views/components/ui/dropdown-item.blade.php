@props(['icon' => null, 'href' => null, 'tone' => 'default'])
@php
$tag = $href ? 'a' : 'button';
$color = $tone === 'danger' ? 'text-danger hover:bg-danger-bg' : 'text-ink_text-primary hover:bg-surface-muted hover:text-ink_text-primary';
$defaults = ['class' => "w-full flex items-center gap-2.5 h-9 px-2.5 rounded-lg text-[13px] font-medium text-left transition-colors $color"];
$href ? $defaults['href'] = $href : $defaults['type'] = 'button';
@endphp
<{{ $tag }} {{ $attributes->merge($defaults) }}>
    @if ($icon)
        <x-ui.icon :name="$icon" :size="15" class="shrink-0 {{ $tone === 'danger' ? '' : 'text-ink_text-muted' }}" />
    @endif
    <span class="truncate">{{ $slot }}</span>
</{{ $tag }}>
