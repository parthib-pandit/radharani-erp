<div>
    <x-ui.page-header title="Vendors" subtitle="Karigars, suppliers, and hallmarking centres." />

    @if (session('message'))
        <div class="bg-success-bg text-success rounded-control px-3.5 py-2.5 mb-5 text-sm">{{ session('message') }}</div>
    @endif

    <x-ui.card class="mb-5">
        <div class="font-semibold text-sm text-ink_text-primary mb-3">{{ $editingId ? 'Edit Vendor' : 'Add Vendor' }}</div>
        <form wire:submit="save" class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Name</label>
                <input type="text" class="rj-input w-full" wire:model="name">
                @error('name') <div class="text-danger text-[11px] mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Type</label>
                <select class="rj-select w-full" wire:model="type">
                    <option value="karigar">Karigar</option>
                    <option value="supplier">Supplier</option>
                    <option value="hallmark_center">Hallmarking Centre</option>
                </select>
            </div>
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Phone</label>
                <input type="text" class="rj-input w-full" wire:model="phone">
            </div>
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Opening / Current Balance (₹)</label>
                <input type="number" step="0.01" class="rj-input w-full" wire:model="balance">
            </div>
            <div class="col-span-full">
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Address</label>
                <input type="text" class="rj-input w-full" wire:model="address">
            </div>
            <div class="col-span-full flex gap-2">
                <x-ui.button type="submit" variant="primary">{{ $editingId ? 'Update Vendor' : 'Add Vendor' }}</x-ui.button>
                @if ($editingId)
                    <x-ui.button type="button" variant="secondary" wire:click="cancel">Cancel</x-ui.button>
                @endif
            </div>
        </form>
    </x-ui.card>

    <x-ui.card class="!p-0 overflow-hidden">
        <div class="flex gap-3 p-5 pb-0">
            <input type="text" class="rj-input max-w-[260px]" placeholder="Search name or phone…" wire:model.live.debounce.400ms="search">
            <select class="rj-select max-w-[200px]" wire:model.live="typeFilter">
                <option value="all">All types</option>
                <option value="karigar">Karigar</option>
                <option value="supplier">Supplier</option>
                <option value="hallmark_center">Hallmarking Centre</option>
            </select>
        </div>

        <x-ui.table :headers="['Name', 'Type', 'Phone', 'Balance', '']">
            @forelse ($vendors as $v)
                <tr class="h-[60px] border-b border-line-light">
                    <td class="px-4 font-semibold text-ink_text-primary">{{ $v->name }}</td>
                    <td class="px-4"><x-ui.badge tone="neutral">{{ str($v->type)->replace('_', ' ')->title() }}</x-ui.badge></td>
                    <td class="px-4 text-ink_text-primary">{{ $v->phone ?: '—' }}</td>
                    <td class="px-4 text-ink_text-primary">₹{{ number_format($v->balance, 2) }}</td>
                    <td class="px-4"><x-ui.button type="button" variant="secondary" wire:click="edit({{ $v->id }})">Edit</x-ui.button></td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-4 text-ink_text-secondary">No vendors yet.</td></tr>
            @endforelse
        </x-ui.table>
        <div class="p-5">{{ $vendors->links() }}</div>
    </x-ui.card>
</div>
