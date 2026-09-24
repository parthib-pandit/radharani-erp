<div>
    <x-ui.page-header title="Purchases" subtitle="Payment status is fixed at entry and cannot be edited here — see note below." />

    <x-ui.card class="!p-0 overflow-hidden">
        <div class="flex gap-3 p-5 pb-0">
            <input type="text" class="rj-input max-w-[280px]" placeholder="Search invoice no. or vendor…" wire:model.live.debounce.400ms="search">
            <select class="rj-select max-w-[180px]" wire:model.live="statusFilter">
                <option value="all">All statuses</option>
                <option value="pending">Pending</option>
                <option value="partial">Partial</option>
                <option value="paid">Paid</option>
            </select>
        </div>

        <x-ui.table :headers="['#', 'Vendor', 'Type', 'Invoice', 'Weight', 'Amount', 'Status', 'Date']">
            @forelse ($purchases as $p)
                <tr class="h-[60px] border-b border-line-light">
                    <td class="px-4 text-ink_text-primary">{{ $p->id }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $p->vendor->name ?? '—' }}</td>
                    <td class="px-4">
                        @if ($p->type === 'raw_material')
                            <x-ui.badge tone="gold">Raw Material</x-ui.badge>
                        @else
                            <x-ui.badge tone="info">Finished Product</x-ui.badge>
                        @endif
                    </td>
                    <td class="px-4 text-ink_text-primary">{{ $p->invoice_number ?: '—' }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $p->total_weight ? number_format($p->total_weight, 3).'g' : '—' }}</td>
                    <td class="px-4 text-ink_text-primary">₹{{ number_format($p->total_amount, 2) }}</td>
                    <td class="px-4">
                        @if ($p->payment_status === 'paid')
                            <x-ui.badge tone="success">Paid</x-ui.badge>
                        @elseif ($p->payment_status === 'partial')
                            <x-ui.badge tone="neutral">Partial</x-ui.badge>
                        @else
                            <x-ui.badge tone="warning">Pending</x-ui.badge>
                        @endif
                    </td>
                    <td class="px-4 text-ink_text-primary">{{ $p->created_at->format('d M Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="8" class="px-4 py-4 text-ink_text-secondary">No purchases recorded yet.</td></tr>
            @endforelse
        </x-ui.table>
        <div class="p-5">{{ $purchases->links() }}</div>
    </x-ui.card>

    <div class="mt-4 bg-gold-soft/40 border border-line rounded-control p-3 text-sm text-gold-dark max-w-[720px]">
        Payment status can't be changed from "pending" to "paid"/"partial" after the purchase is saved — CLAUDE.md's rule against
        ever updating a purchases row applies here the same way it does to sales. Recording a payment update would need a
        correction mechanism (a new row referencing this one, owner-approved), which doesn't exist yet. Flagging rather than
        quietly adding an edit button that breaks the rule.
    </div>
</div>
