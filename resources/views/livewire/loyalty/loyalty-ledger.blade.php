<div>
    <x-ui.page-header title="Loyalty Points Ledger" subtitle="Full history of points earned and redeemed, across all customers." />

    <input type="text" wire:model.live.debounce.400ms="search" placeholder="Search customer name or phone…"
        class="rj-input mb-3.5 max-w-[280px]">

    <x-ui.card class="!p-0 overflow-hidden">
        <x-ui.table :headers="['Date', 'Customer', 'Reason', 'Sale', 'Points']">
            @forelse ($transactions as $t)
                <tr class="h-[60px] border-b border-line-light">
                    <td class="px-4 text-ink_text-primary">{{ $t->created_at->format('d M Y') }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $t->customer->name ?? '—' }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $t->reason ?: '—' }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $t->related_sale_id ? '#'.$t->related_sale_id : '—' }}</td>
                    <td class="px-4 font-semibold {{ $t->points >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ $t->points >= 0 ? '+' : '' }}{{ $t->points }}
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-4 text-ink_text-secondary">No loyalty activity yet.</td></tr>
            @endforelse
        </x-ui.table>
    </x-ui.card>

    <div class="mt-4">{{ $transactions->links() }}</div>
</div>
