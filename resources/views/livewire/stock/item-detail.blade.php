<div>
    <a href="{{ route('stock.items') }}" class="text-xs text-ink_text-secondary font-semibold">← All items</a>

    <div class="flex justify-between items-start mt-2.5 mb-6">
        <div>
            <div class="text-2xl font-bold text-ink_text-primary">{{ $item->huid_code ?: $item->internal_code }}</div>
            <div class="text-sm text-ink_text-secondary mt-0.5">{{ $item->category }} · {{ $item->purity }} · {{ $item->weight }}g</div>
        </div>
        <x-ui.badge :tone="$item->status === 'in_stock' ? 'success' : ($item->status === 'dispatched' ? 'warning' : 'neutral')" class="!text-xs !px-3.5 !h-auto !py-1.5">
            {{ strtoupper(str_replace('_',' ',$item->status)) }}
        </x-ui.badge>
    </div>

    <div class="grid grid-cols-2 gap-5 mb-6">
        <x-ui.card>
            <div class="font-semibold text-[13.5px] text-ink_text-primary mb-3">Item Details</div>
            <div class="grid grid-cols-2 gap-2.5 text-[12.5px] text-ink_text-primary">
                <div><span class="text-ink_text-secondary">HUID</span><br>{{ $item->huid_code ?: '— (internal: ' . $item->internal_code . ')' }}</div>
                <div><span class="text-ink_text-secondary">HSN Code</span><br>{{ $item->hsn_code ?: '—' }}</div>
                <div><span class="text-ink_text-secondary">Packet</span><br>{{ $item->packet?->code ?? '— (unassigned)' }}</div>
                <div><span class="text-ink_text-secondary">Box</span><br>{{ $item->packet?->box?->code ?? '—' }}</div>
                <div class="col-span-full"><span class="text-ink_text-secondary">Description</span><br>{{ $item->description ?: '—' }}</div>
                @if ($pair)
                <div class="col-span-full pt-2 border-t border-line-light">
                    <span class="text-ink_text-secondary">Paired with</span><br>
                    <a href="{{ route('stock.items.show', $pair) }}" class="text-gold font-bold">{{ $pair->huid_code ?: $pair->internal_code }}</a> ({{ $pair->weight }}g)
                </div>
                @endif
            </div>
        </x-ui.card>

        <x-ui.card>
            <div class="font-semibold text-[13.5px] text-ink_text-primary mb-3">Pricing Setup</div>
            <div class="grid grid-cols-2 gap-2.5 text-[12.5px] text-ink_text-primary">
                <div><span class="text-ink_text-secondary">Making Type</span><br>{{ $item->making_type === 'percentage' ? 'Percentage of metal value' : 'Flat per piece' }}</div>
                <div><span class="text-ink_text-secondary">Making Value</span><br>{{ $item->making_type === 'percentage' ? $item->making_value.'%' : '₹'.number_format($item->making_value,2) }}</div>
            </div>
            <div class="text-[11px] text-ink_text-secondary mt-3 pt-2.5 border-t border-line-light">
                Live sale price is computed at billing time via the pricing service using the day's metal rate — never stored here.
            </div>
        </x-ui.card>
    </div>

    <x-ui.card>
        <div class="font-semibold text-[13.5px] text-ink_text-primary mb-4">Movement History</div>
        @if ($movements->isEmpty())
            <div class="text-sm text-ink_text-secondary">No movements recorded yet — this item has been in the vault since creation.</div>
        @else
        <div class="relative pl-[22px]">
            <div class="absolute left-[5px] top-1 bottom-1 w-0.5 bg-line"></div>
            @foreach ($movements as $m)
                <div class="relative pb-5">
                    <div class="absolute -left-[22px] top-0.5 w-3 h-3 rounded-full border-2 border-white {{ str_ends_with($m->movement_type,'_out') ? 'bg-danger' : 'bg-success' }}"></div>
                    <div class="text-[12.5px] font-bold text-ink_text-primary">{{ ucwords(str_replace('_',' ',$m->movement_type)) }}{{ $m->purpose_label ? ' — '.$m->purpose_label : '' }}</div>
                    <div class="text-[11.5px] text-ink_text-secondary mt-0.5">
                        {{ $m->created_at->format('d M Y, g:i a') }} · by {{ $m->user->name ?? '—' }}
                        @if ($m->counterparty) · {{ $m->counterparty }} @endif
                        @if ($m->weight_at_dispatch) · {{ $m->weight_at_dispatch }}g @endif
                    </div>
                    @if ($m->note)
                        <div class="text-xs text-ink_text-primary mt-1">{{ $m->note }}</div>
                    @endif
                </div>
            @endforeach
        </div>
        @endif
    </x-ui.card>
</div>
