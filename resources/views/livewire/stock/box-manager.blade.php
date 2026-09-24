<div>
    <x-ui.page-header title="Box Management" subtitle="Top-level storage containers — packets live inside boxes." />

    @if (session('message'))
        <div class="bg-success-bg text-success rounded-control px-3.5 py-2.5 mb-5 text-sm">{{ session('message') }}</div>
    @endif

    <x-ui.card class="mb-6">
        <div class="font-semibold text-sm text-ink_text-primary mb-4">{{ $editingId ? 'Edit Box' : 'New Box' }}</div>
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
            <x-ui.button type="submit" variant="primary">Save</x-ui.button>
            @if ($editingId)
                <x-ui.button type="button" wire:click="cancel" variant="secondary">Cancel</x-ui.button>
            @endif
        </form>
    </x-ui.card>

    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search boxes..."
        class="rj-input mb-3.5 w-[280px]">

    <x-ui.card class="!p-0 overflow-hidden">
        <x-ui.table :headers="['Code', 'Label', '# Packets', '']">
            @foreach ($boxes as $box)
                <tr class="h-[60px] border-b border-line-light">
                    <td class="px-4 font-semibold text-ink_text-primary">{{ $box->code }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $box->label }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $box->packets_count }}</td>
                    <td class="px-4 text-right whitespace-nowrap">
                        <a href="{{ route('stock.boxes.show', $box) }}" class="text-gold font-semibold text-xs mr-3.5">View</a>
                        <button wire:click="edit({{ $box->id }})" class="bg-transparent border-0 text-gold font-semibold text-xs cursor-pointer">Edit</button>
                    </td>
                </tr>
            @endforeach
        </x-ui.table>
    </x-ui.card>

    <div class="mt-4">{{ $boxes->links() }}</div>
</div>
