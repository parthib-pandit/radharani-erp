<div>
    <x-ui.page-header title="Packet Management" subtitle="Packets group items and sit inside a box." />

    @if (session('message'))
        <div class="bg-success-bg text-success rounded-control px-3.5 py-2.5 mb-5 text-sm">{{ session('message') }}</div>
    @endif

    <x-ui.card class="mb-6">
        <div class="font-semibold text-sm text-ink_text-primary mb-4">{{ $editingId ? 'Edit Packet' : 'New Packet' }}</div>
        <form wire:submit="save" class="flex flex-wrap gap-3.5 items-end">
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Code</label>
                <input type="text" wire:model="code" class="rj-input">
                @error('code') <div class="text-danger text-[11px] mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Label</label>
                <input type="text" wire:model="label" class="rj-input">
            </div>
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Box</label>
                <select wire:model="box_id" class="rj-select">
                    <option value="">— none —</option>
                    @foreach ($boxes as $box)
                        <option value="{{ $box->id }}">{{ $box->code }}</option>
                    @endforeach
                </select>
            </div>
            <x-ui.button type="submit" variant="primary">Save</x-ui.button>
            @if ($editingId)
                <x-ui.button type="button" wire:click="cancel" variant="secondary">Cancel</x-ui.button>
            @endif
        </form>
    </x-ui.card>

    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search packets..."
        class="rj-input mb-3.5 w-[280px]">

    <x-ui.card class="!p-0 overflow-hidden">
        <x-ui.table :headers="['Code', 'Box', '# Items', '']">
            @foreach ($packets as $packet)
                <tr class="h-[60px] border-b border-line-light">
                    <td class="px-4 font-semibold text-ink_text-primary">{{ $packet->code }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $packet->box?->code ?? '—' }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $packet->items_count }}</td>
                    <td class="px-4 text-right whitespace-nowrap">
                        <a href="{{ route('stock.packets.show', $packet) }}" class="text-gold font-semibold text-xs mr-3.5">View</a>
                        <button wire:click="edit({{ $packet->id }})" class="bg-transparent border-0 text-gold font-semibold text-xs cursor-pointer">Edit</button>
                    </td>
                </tr>
            @endforeach
        </x-ui.table>
    </x-ui.card>

    <div class="mt-4">{{ $packets->links() }}</div>
</div>
