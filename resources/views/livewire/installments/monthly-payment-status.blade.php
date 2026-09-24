<div>
    <x-ui.page-header title="Monthly Payment Status — {{ now()->format('F Y') }}" subtitle="Mark each active scheme paid or not-paid for this month." />

    @if ($justMarked)
        <div class="bg-success-bg text-success rounded-control px-3.5 py-2.5 mb-5 text-sm">{{ $justMarked }}</div>
    @endif

    <x-ui.card class="!p-0 overflow-hidden">
        <x-ui.table :headers="['Customer', 'Monthly Amount', 'Months Paid', 'This Month', '']">
            @forelse ($schemes as $scheme)
                <tr class="h-[60px] border-b border-line-light">
                    <td class="px-4 text-ink_text-primary">{{ $scheme->customer->name ?? '—' }}</td>
                    <td class="px-4 text-ink_text-primary">₹{{ number_format($scheme->monthly_amount, 2) }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $scheme->months_paid }}</td>
                    <td class="px-4">
                        @if ($scheme->paidThisMonth)
                            <x-ui.badge tone="success">Paid</x-ui.badge>
                        @else
                            <x-ui.badge tone="warning">Not Paid</x-ui.badge>
                        @endif
                    </td>
                    <td class="px-4 text-right whitespace-nowrap">
                        @unless ($scheme->paidThisMonth)
                            <x-ui.button type="button" wire:click="sendReminder({{ $scheme->id }})" variant="secondary" class="mr-2">Send Reminder</x-ui.button>
                            <x-ui.button type="button" wire:click="markPaid({{ $scheme->id }})" variant="primary">Mark Paid</x-ui.button>
                        @endunless
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-4 text-ink_text-secondary">No active schemes.</td></tr>
            @endforelse
        </x-ui.table>
    </x-ui.card>
</div>
