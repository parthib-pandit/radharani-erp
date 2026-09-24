<div>
    <a href="{{ route('stock.boxes') }}" class="text-xs text-ink_text-secondary font-semibold">← All boxes</a>

    <div class="mt-2.5 mb-6">
        <div class="text-2xl font-bold text-ink_text-primary">{{ $box->code }}</div>
        <div class="text-sm text-ink_text-secondary mt-0.5">{{ $box->label }}</div>
    </div>

    <x-ui.card class="mb-6">
        <div class="font-semibold text-[13.5px] text-ink_text-primary mb-3">Packets Inside ({{ $box->packets->count() }})</div>
        @if ($box->packets->isEmpty())
            <div class="text-sm text-ink_text-secondary">No packets in this box yet.</div>
        @else
        <x-ui.table :headers="['Code', 'Label', 'Items', '']">
            @foreach ($box->packets as $packet)
                <tr class="h-[60px] border-b border-line-light">
                    <td class="px-4 font-semibold text-gold">{{ $packet->code }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $packet->label }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $packet->items->count() }}</td>
                    <td class="px-4 text-right whitespace-nowrap"><a href="{{ route('stock.packets.show', $packet) }}" class="text-gold font-semibold text-xs">View</a></td>
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
