<div>
    <x-ui.page-header title="Daily Logbook" subtitle="Movements and sales for the day, combined into one timeline." />

    <input type="date" wire:model.live="date" class="rj-input mb-3.5 max-w-[200px]">

    <x-ui.card class="!p-0 overflow-hidden">
        <x-ui.table :headers="['Time', 'Type', 'Event', 'By / Customer', 'Note']">
            @forelse ($timeline as $entry)
                <tr class="h-[60px] border-b border-line-light">
                    <td class="px-4 text-ink_text-primary">{{ $entry['time']?->format('H:i') }}</td>
                    <td class="px-4">
                        @if ($entry['kind'] === 'sale')
                            <x-ui.badge tone="success">Sale</x-ui.badge>
                        @else
                            <x-ui.badge tone="neutral">Movement</x-ui.badge>
                        @endif
                    </td>
                    <td class="px-4 text-ink_text-primary">{{ $entry['label'] }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $entry['by'] }}</td>
                    <td class="px-4 text-ink_text-secondary text-xs">{{ $entry['note'] }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-4 text-ink_text-secondary">Nothing recorded on this date.</td></tr>
            @endforelse
        </x-ui.table>
    </x-ui.card>
</div>
