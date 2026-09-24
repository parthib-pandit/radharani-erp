<div>
    <x-ui.page-header title="Sales List / History" subtitle="Searchable record of past sales." />

    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search invoice # or customer..." class="rj-input w-[300px] mb-4">

    <x-ui.card class="!p-0 overflow-hidden">
        <x-ui.table :headers="['Invoice', 'Customer', 'Date', 'Total', 'Status', '']">
            @forelse ($sales as $sale)
            <tr class="h-[60px] border-b border-line-light">
                <td class="px-4 font-mono text-[11.5px] text-ink_text-primary">{{ $sale->invoice_number }}</td>
                <td class="px-4 text-ink_text-primary">{{ $sale->customer->name ?? '—' }}</td>
                <td class="px-4 text-ink_text-primary">{{ \Carbon\Carbon::parse($sale->created_at)->format('d M Y') }}</td>
                <td class="px-4 text-ink_text-primary">₹{{ number_format($sale->total,2) }}</td>
                <td class="px-4">
                    <x-ui.badge :tone="$sale->confirmed_by_accountant ? 'success' : 'warning'">
                        {{ $sale->confirmed_by_accountant ? 'VERIFIED' : 'RESERVED' }}
                    </x-ui.badge>
                </td>
                <td class="px-4 text-right">
                    <a href="{{ route('sales.invoice', $sale) }}" class="text-gold font-semibold text-xs">View</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-5 py-4 text-ink_text-secondary">No sales yet.</td></tr>
            @endforelse
        </x-ui.table>
    </x-ui.card>
    <div class="mt-4">{{ $sales->links() }}</div>
</div>
