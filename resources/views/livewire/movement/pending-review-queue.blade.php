<div>
    <x-ui.page-header title="Pending Review — Admin Review Queue" subtitle="Items that have returned from karigar or hallmarking and are awaiting admin confirmation back into stock." />

    @if (session('message'))
        <div class="bg-success-bg text-success rounded-control px-3.5 py-2.5 mb-5 text-sm">{{ session('message') }}</div>
    @endif

    <x-ui.card class="!p-0 overflow-hidden mb-6">
        <div class="px-5 py-3.5 font-bold text-[13px] border-b border-line">Pending Review</div>
        <x-ui.table :headers="['Item', 'Returned from', 'Since', 'Weight loss', 'Tagged by', '']">
            @forelse ($pendingItems as $row)
            <tr class="h-[60px] border-b border-line-light">
                <td class="px-4 font-semibold text-gold">
                    <a href="{{ route('stock.items.show', $row['item']) }}" class="text-gold">{{ $row['item']->huid_code ?: $row['item']->internal_code }}</a>
                    <x-ui.badge tone="warning" class="ml-2">Pending Review</x-ui.badge>
                </td>
                <td class="px-4 text-ink_text-primary">{{ $row['movement'] ? ucwords(str_replace(['_in','_'], ['',' '], $row['movement']->movement_type)) : '—' }}</td>
                <td class="px-4 text-ink_text-primary">{{ $row['movement']?->created_at?->diffForHumans() ?? '—' }}</td>
                <td class="px-4 text-ink_text-primary">{{ $row['movement']?->weight_loss !== null ? $row['movement']->weight_loss.'g' : '—' }}</td>
                <td class="px-4 text-ink_text-primary">{{ $row['movement']?->tagged_by ?? '—' }}</td>
                <td class="px-4 text-right whitespace-nowrap">
                    <x-ui.button wire:click="confirmClose({{ $row['item']->id }})" variant="primary">Confirm Into Stock</x-ui.button>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-5 py-4 text-ink_text-secondary">Nothing awaiting review right now.</td></tr>
            @endforelse
        </x-ui.table>
    </x-ui.card>
</div>
