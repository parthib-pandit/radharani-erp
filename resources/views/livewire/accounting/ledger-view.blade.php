<div>
    <x-ui.page-header title="Ledger / Transactions" subtitle="Auto-generated from sales, purchases, and installment payments. Read-only." />

    <div class="flex gap-3 mb-4">
        <select wire:model.live="accountFilter" class="rj-select max-w-[220px]">
            <option value="all">All accounts</option>
            @foreach ($accounts as $a)
                <option value="{{ $a->id }}">{{ $a->name }} ({{ ucfirst($a->type) }})</option>
            @endforeach
        </select>
        <select wire:model.live="typeFilter" class="rj-select max-w-[200px]">
            <option value="all">All reference types</option>
            <option value="sale">Sale</option>
            <option value="purchase">Purchase</option>
            <option value="installment">Installment</option>
            <option value="manual">Manual</option>
        </select>
    </div>

    <x-ui.card class="!p-0 overflow-hidden">
        <x-ui.table :headers="['Date', 'Account', 'Reference', 'Debit', 'Credit', 'By']">
            @forelse ($transactions as $t)
                <tr class="h-[60px] border-b border-line-light">
                    <td class="px-4 text-ink_text-primary">{{ $t->created_at->format('d M Y H:i') }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $t->account->name ?? '—' }}</td>
                    <td class="px-4">
                        <x-ui.badge tone="neutral">{{ ucfirst($t->reference_type) }}</x-ui.badge>
                        <span class="text-ink_text-secondary text-xs">#{{ $t->reference_id ?? '—' }}</span>
                    </td>
                    <td class="px-4 text-ink_text-primary">{{ $t->debit > 0 ? '₹'.number_format($t->debit, 2) : '—' }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $t->credit > 0 ? '₹'.number_format($t->credit, 2) : '—' }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $t->creator->name ?? '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-4 py-4 text-ink_text-secondary">No transactions recorded yet.</td></tr>
            @endforelse
        </x-ui.table>
    </x-ui.card>

    <div class="mt-4">{{ $transactions->links() }}</div>
</div>
