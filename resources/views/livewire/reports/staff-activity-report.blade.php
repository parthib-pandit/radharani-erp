<div>
    <x-ui.page-header title="Staff-wise Activity Report" subtitle="Movements logged and sales created, per staff member." />

    <div class="flex gap-3 mb-3.5">
        <select wire:model.live="staffId" class="rj-select max-w-[220px]">
            <option value="">All staff</option>
            @foreach ($staffOptions as $s)
                <option value="{{ $s->id }}">{{ $s->name }}</option>
            @endforeach
        </select>
        <input type="date" wire:model.live="fromDate" class="rj-input max-w-[170px]">
        <input type="date" wire:model.live="toDate" class="rj-input max-w-[170px]">
    </div>

    <x-ui.card class="!p-0 overflow-hidden">
        <x-ui.table :headers="['Staff', 'Movements Logged', 'Sales Created', 'Sales Value']">
            @forelse ($rows as $row)
                <tr class="h-[60px] border-b border-line-light">
                    <td class="px-4 text-ink_text-primary">{{ $row['user']->name }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $row['movements'] }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $row['sales'] }}</td>
                    <td class="px-4 text-ink_text-primary">₹{{ number_format($row['sales_amount'], 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-4 py-4 text-ink_text-secondary">No staff found.</td></tr>
            @endforelse
        </x-ui.table>
    </x-ui.card>
</div>
