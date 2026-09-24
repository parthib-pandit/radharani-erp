@props(['icon' => 'box', 'label', 'value', 'delta' => null, 'deltaPositive' => true])
<div {{ $attributes->merge(['class' => 'flex-1 min-w-0 bg-white border border-line-light rounded-card p-[18px] shadow-card flex flex-col gap-3.5']) }}>
    <div class="flex items-center gap-2.5">
        <div class="w-[38px] h-[38px] shrink-0 rounded-control bg-[#FFF7E6] text-gold flex items-center justify-center">
            <x-ui.icon :name="$icon" :size="17" />
        </div>
        <div class="text-[13px] font-medium text-ink_text-secondary">{{ $label }}</div>
    </div>
    <div class="text-[26px] font-semibold text-ink_text-primary leading-none">{{ $value }}</div>
    @if($delta)
        <div class="text-xs font-medium {{ $deltaPositive ? 'text-success' : 'text-danger' }}">
            {{ $deltaPositive ? '↑' : '↓' }} {{ $delta }}
        </div>
    @endif
</div>
