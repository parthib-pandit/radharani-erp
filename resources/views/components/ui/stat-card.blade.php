@props(['icon' => 'box', 'label', 'value', 'hint' => null, 'delta' => null, 'deltaPositive' => true, 'href' => null])
@php $tag = $href ? 'a' : 'div'; @endphp
<{{ $tag }} @if($href) href="{{ $href }}" @endif
    {{ $attributes->merge(['class' => 'group relative flex-1 min-w-0 bg-white border border-line-light rounded-card p-4 sm:p-5 shadow-card flex flex-col gap-2.5 sm:gap-3 overflow-hidden transition-[border-color,box-shadow] duration-200' . ($href ? ' hover:border-gold-soft hover:shadow-raised' : '')]) }}>
    <div class="flex items-start justify-between gap-3">
        <div class="text-[12.5px] font-semibold text-ink_text-secondary">{{ $label }}</div>
        <div class="w-9 h-9 shrink-0 rounded-[10px] bg-gold-tint text-gold-dark ring-1 ring-inset ring-gold-soft/60 flex items-center justify-center">
            <x-ui.icon :name="$icon" :size="16" />
        </div>
    </div>
    <div class="font-display text-[28px] sm:text-[34px] leading-none font-semibold text-ink_text-primary tabular">{{ $value }}</div>
    @if ($delta || $hint)
        <div class="flex items-center gap-2 text-[12px]">
            @if ($delta)
                <span class="font-semibold {{ $deltaPositive ? 'text-success' : 'text-danger' }}">{{ $deltaPositive ? '↑' : '↓' }} {{ $delta }}</span>
            @endif
            @if ($hint)
                <span class="text-ink_text-muted truncate">{{ $hint }}</span>
            @endif
        </div>
    @endif
</{{ $tag }}>
