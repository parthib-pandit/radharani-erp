{{-- One piece on the bill with its whole price breakup. Used by the This bill step and by Review. Needs $line and $id. --}}
@php $weightChanged = abs($line['weight'] - $line['weight_listed']) > 0.0004; @endphp
<div class="rounded-xl ring-1 ring-inset ring-line-light p-3.5" wire:key="pb-{{ $id }}">
    <div class="flex flex-wrap items-baseline justify-between gap-x-3 gap-y-1">
        <div><span class="rj-code text-ink_text-primary">{{ $line['label'] }}</span> <span class="text-[12.5px] text-ink_text-muted">{{ $line['category'] }}</span></div>
        <div class="text-[12.5px] tabular text-ink_text-secondary">{{ number_format($line['weight'], 3) }} g
            @if ($weightChanged)<span class="text-warning font-semibold"> (record {{ number_format($line['weight_listed'], 3) }} g)</span>@endif</div>
    </div>
    @if ($weightChanged && trim($line['weight_note']) !== '')<p class="text-[12px] text-ink_text-muted mt-0.5">Weight: {{ $line['weight_note'] }}</p>@endif
    <div class="mt-2 space-y-1 text-[12.5px]">
        @foreach ($line['breakup'] ?? [] as $bl)
            <div class="flex justify-between gap-3"><span>{{ $bl['label'] }}@if ($bl['note'])<span class="text-ink_text-muted text-[11.5px]"> · {{ $bl['note'] }}</span>@endif</span><span class="tabular whitespace-nowrap {{ $bl['amount'] < 0 ? 'text-success' : '' }}">{{ $bl['amount'] < 0 ? '- ' : '' }}₹{{ number_format(abs($bl['amount']), 2) }}</span></div>
        @endforeach
        @if ($line['making_saved'] > 0)<div class="text-danger font-semibold text-[12px]">Making charge reduced by ₹{{ number_format($line['making_saved'], 2) }}</div>@endif
        <div class="flex justify-between gap-3 pt-1 border-t border-line-light font-semibold"><span>Piece price before GST</span><span class="tabular">₹{{ number_format($line['price'], 2) }}</span></div>
    </div>
</div>
