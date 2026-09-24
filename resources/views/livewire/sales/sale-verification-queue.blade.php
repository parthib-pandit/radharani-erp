<div>
    <x-ui.page-header title="Sale Verification Queue" subtitle="Admin only — verifying here confirms the sale, flips its items from reserved to sold, and queues the customer notification." />

    <div class="bg-warning-bg text-warning rounded-control px-3.5 py-2.5 mb-5 max-w-[820px] text-xs">
        Flag: the real invoice number is still only assigned at creation (see the note on New Sale) — rule 1 forbids updating any other <code>sales</code> column after creation, so verifying does not change <code>invoice_number</code>. <code>confirmed_by_accountant</code> is the one flag this schema was built to flip.
    </div>

    <x-ui.card class="!p-0 overflow-hidden">
        <x-ui.table :headers="['Reserved as', 'Customer', 'Items', 'Total', 'By', '']">
            @forelse ($pending as $sale)
            <tr class="h-[60px] border-b border-line-light" wire:key="pending-sale-{{ $sale->id }}">
                <td class="px-4 font-mono text-[11.5px] text-ink_text-primary">{{ $sale->invoice_number }}</td>
                <td class="px-4 text-ink_text-primary">{{ $sale->customer->name ?? '—' }}</td>
                <td class="px-4 text-ink_text-primary">{{ $sale->items->count() }} item(s)</td>
                <td class="px-4 text-ink_text-primary">₹{{ number_format($sale->total,2) }}</td>
                <td class="px-4 text-ink_text-primary">{{ $sale->creator->name ?? '—' }}</td>
                <td class="px-4 text-right">
                    <x-ui.button wire:click="verify({{ $sale->id }})" wire:confirm="Confirm this sale? Its items will be marked sold and the customer notified." variant="primary" class="!h-8 !px-3.5 !text-[11.5px]">Approve</x-ui.button>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-5 py-4 text-ink_text-secondary">Nothing waiting on verification.</td></tr>
            @endforelse
        </x-ui.table>
    </x-ui.card>
</div>
