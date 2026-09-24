<div>
    <x-ui.page-header title="Installment Schemes" subtitle="All enrolments, filterable by status." />

    <select wire:model.live="statusFilter" class="rj-select mb-3.5 max-w-[200px]">
        <option value="all">All statuses</option>
        <option value="active">Active</option>
        <option value="completed">Completed</option>
        <option value="defaulted">Defaulted</option>
    </select>

    <x-ui.card class="!p-0 overflow-hidden">
        <x-ui.table :headers="['Customer', 'Monthly Amount', 'Months Paid', 'Started', 'Status', '']">
            @forelse ($schemes as $scheme)
                <tr class="h-[60px] border-b border-line-light">
                    <td class="px-4 text-ink_text-primary">{{ $scheme->customer->name ?? '—' }}</td>
                    <td class="px-4 text-ink_text-primary">₹{{ number_format($scheme->monthly_amount, 2) }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $scheme->months_paid }}</td>
                    <td class="px-4 text-ink_text-primary">{{ \Illuminate\Support\Carbon::parse($scheme->start_date)->format('d M Y') }}</td>
                    <td class="px-4">
                        <x-ui.badge :tone="$scheme->status === 'active' ? 'success' : ($scheme->status === 'completed' ? 'info' : 'danger')">
                            {{ ucfirst($scheme->status) }}
                        </x-ui.badge>
                    </td>
                    <td class="px-4 text-right whitespace-nowrap">
                        @if ($scheme->status === 'active')
                            <x-ui.button type="button" wire:click="markStatus({{ $scheme->id }}, 'completed')" variant="secondary" class="mr-2">Mark Completed</x-ui.button>
                            <x-ui.button type="button" wire:click="markStatus({{ $scheme->id }}, 'defaulted')" variant="secondary">Mark Defaulted</x-ui.button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-4 py-4 text-ink_text-secondary">No schemes yet.</td></tr>
            @endforelse
        </x-ui.table>
    </x-ui.card>

    <div class="mt-4">{{ $schemes->links() }}</div>
</div>
