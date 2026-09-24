<div>
    <a href="{{ route('stock.packets') }}" class="text-xs text-ink_text-secondary font-semibold">← All packets</a>

    <div class="mt-2.5 mb-6">
        <div class="text-2xl font-bold text-ink_text-primary">{{ $packet->code }}</div>
        <div class="text-sm text-ink_text-secondary mt-0.5">{{ $packet->label }} · in box {{ $packet->box?->code ?? '— (unassigned)' }}</div>
    </div>

    <x-ui.card class="!p-0 overflow-hidden mb-6">
        <div class="flex justify-between items-center px-5 pt-5 pb-3">
            <div class="font-semibold text-[13.5px] text-ink_text-primary">Contents ({{ $packet->items->count() }} item{{ $packet->items->count() === 1 ? '' : 's' }})</div>
            <a href="{{ route('stock.assign') }}" class="text-xs text-gold font-bold">+ Assign more items</a>
        </div>
        @if ($packet->items->isEmpty())
            <div class="text-sm text-ink_text-secondary px-5 pb-5">No items assigned to this packet yet.</div>
        @else
        <x-ui.table :headers="['HUID / Code', 'Category', 'Weight', 'Status', '']">
            @foreach ($packet->items as $item)
            <tr class="h-[60px] border-b border-line-light">
                <td class="px-4 font-semibold text-gold">{{ $item->huid_code ?: $item->internal_code }}</td>
                <td class="px-4 text-ink_text-primary">{{ $item->category }}</td>
                <td class="px-4 text-ink_text-primary">{{ $item->weight }}g</td>
                <td class="px-4">
                    <x-ui.badge :tone="$item->status === 'in_stock' ? 'success' : ($item->status === 'dispatched' ? 'warning' : 'neutral')">
                        {{ strtoupper(str_replace('_',' ',$item->status)) }}
                    </x-ui.badge>
                </td>
                <td class="px-4 text-right whitespace-nowrap"><a href="{{ route('stock.items.show', $item) }}" class="text-gold font-bold text-xs">View</a></td>
            </tr>
            @endforeach
        </x-ui.table>
        @endif
    </x-ui.card>

    <x-ui.card>
        <div class="font-semibold text-[13.5px] text-ink_text-primary mb-4">Movement History</div>
        @if ($movements->isEmpty())
            <div class="text-sm text-ink_text-secondary">No movements recorded yet.</div>
        @else
        <div class="relative pl-[22px]">
            <div class="absolute left-[5px] top-1 bottom-1 w-0.5 bg-line"></div>
            @foreach ($movements as $m)
                <div class="relative pb-5">
                    <div class="absolute -left-[22px] top-0.5 w-3 h-3 rounded-full border-2 border-white {{ str_ends_with($m->movement_type,'_out') ? 'bg-danger' : 'bg-success' }}"></div>
                    <div class="text-[12.5px] font-bold text-ink_text-primary">{{ ucwords(str_replace('_',' ',$m->movement_type)) }}{{ $m->purpose_label ? ' — '.$m->purpose_label : '' }}</div>
                    <div class="text-[11.5px] text-ink_text-secondary mt-0.5">{{ $m->created_at->format('d M Y, g:i a') }} · by {{ $m->user->name ?? '—' }}</div>
                </div>
            @endforeach
        </div>
        @endif
    </x-ui.card>
</div>
