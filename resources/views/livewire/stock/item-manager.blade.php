<div>
    <x-ui.page-header title="Inventory" subtitle="Full item list — HUID or auto-generated code, purity, weight, status.">
        <x-slot:actions>
            <a href="{{ route('stock.assign') }}"><x-ui.button variant="secondary" icon="package">Assign to Packet</x-ui.button></a>
            <a href="{{ route('stock.qr-codes') }}"><x-ui.button variant="secondary" icon="qr-code">QR Codes</x-ui.button></a>
            <a href="{{ route('stock.import') }}"><x-ui.button variant="secondary" icon="upload">Bulk Import</x-ui.button></a>
            <a href="{{ route('stock.configurator') }}"><x-ui.button variant="secondary" icon="settings">Hierarchy Configurator</x-ui.button></a>
        </x-slot:actions>
    </x-ui.page-header>

    @if (session('message'))
        <div class="bg-success-bg text-success rounded-control px-3.5 py-2.5 mb-5 text-sm">{{ session('message') }}</div>
    @endif

    <x-ui.card class="mb-6">
        <div class="font-semibold text-sm text-ink_text-primary mb-4">
            {{ $editingId ? 'Edit Item' : ($taggingPurchaseItemId ? 'Tag Purchase Line as Item' : 'New Item') }}
        </div>
        @if ($taggingPurchaseItemId)
            <div class="bg-gold-soft/40 border border-line rounded-control p-3 mb-4 text-sm text-gold-dark">
                Pre-filled from a pending raw-material purchase line. Saving creates the item, links it back to that
                purchase line, and marks the line as tagged.
            </div>
        @endif
        <form wire:submit="save" class="grid grid-cols-4 gap-4">
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Packet</label>
                <select wire:model="packet_id" class="rj-select w-full">
                    <option value="">— none —</option>
                    @foreach ($packets as $packet)
                        <option value="{{ $packet->id }}">{{ $packet->code }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">HUID (blank if none)</label>
                <input type="text" wire:model="huid_code" class="rj-input w-full">
            </div>
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Metal</label>
                <select wire:model="metal" class="rj-select w-full">
                    <option value="gold">Gold</option>
                    <option value="silver">Silver</option>
                    <option value="titanium">Titanium</option>
                    <option value="platinum">Platinum</option>
                </select>
                @error('metal') <div class="text-danger text-[11px] mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Category</label>
                <input type="text" wire:model="category" class="rj-input w-full">
                @error('category') <div class="text-danger text-[11px] mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Purity</label>
                <input type="text" wire:model="purity" placeholder="22K / 92.5 silver" class="rj-input w-full">
                @error('purity') <div class="text-danger text-[11px] mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Weight (g)</label>
                <input type="number" step="0.001" wire:model="weight" class="rj-input w-full">
                @error('weight') <div class="text-danger text-[11px] mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">HSN Code</label>
                <input type="text" wire:model="hsn_code" class="rj-input w-full">
            </div>
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Making Type</label>
                <select wire:model="making_type" class="rj-select w-full">
                    <option value="flat_per_piece">Flat / Per Piece</option>
                    <option value="flat_per_gram">Flat / Per Gram</option>
                    <option value="percentage">Percentage</option>
                </select>
            </div>
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Making Value</label>
                <input type="number" step="0.01" wire:model="making_value" class="rj-input w-full">
            </div>
            <div class="col-span-2">
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Description (short)</label>
                <input type="text" wire:model="description" maxlength="100" class="rj-input w-full">
            </div>
            @if (!$editingId)
            <div class="flex items-center gap-2">
                <input type="checkbox" wire:model="has_pair" id="has_pair" class="accent-gold">
                <label for="has_pair" class="text-[12.5px] text-ink_text-primary">Create as pair (earrings/bangles)</label>
            </div>
            @endif
            <div class="col-span-full flex gap-2.5">
                <x-ui.button type="submit" variant="primary">Save</x-ui.button>
                @if ($editingId || $taggingPurchaseItemId)
                    <x-ui.button type="button" wire:click="cancel" variant="secondary">Cancel</x-ui.button>
                @endif
            </div>
        </form>
    </x-ui.card>

    <x-ui.card class="mb-6">
        <div class="font-semibold text-sm text-ink_text-primary mb-4">Pending Tags <span class="text-ink_text-secondary font-normal">— raw-material purchase lines not yet converted into items</span></div>
        <x-ui.table :headers="['Purchase', 'Vendor', 'Description', 'Category', 'Metal', 'Purity', 'Weight', '']">
            @forelse ($pendingTags as $line)
                <tr class="h-[56px] border-b border-line-light">
                    <td class="px-4 text-ink_text-primary">#{{ $line->purchase_id }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $line->purchase?->vendor?->name ?? '—' }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $line->description ?: '—' }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $line->category ?: '—' }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $line->metal ? ucfirst($line->metal) : '—' }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $line->purity ?: '—' }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $line->weight ? number_format($line->weight, 3).'g' : '—' }}</td>
                    <td class="px-4 text-right">
                        <button wire:click="tagFromPurchaseLine({{ $line->id }})" class="bg-transparent border-0 text-gold font-semibold text-xs cursor-pointer">Tag as Item</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="px-4 py-4 text-ink_text-secondary">No pending raw-material lines to tag.</td></tr>
            @endforelse
        </x-ui.table>
    </x-ui.card>

    <div class="flex gap-3 mb-4">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search HUID / code / category..."
            class="rj-input w-[280px]">
        <select wire:model.live="statusFilter" class="rj-select">
            <option value="">All statuses</option>
            <option value="in_stock">In Stock</option>
            <option value="dispatched">Dispatched</option>
            <option value="sold">Sold</option>
        </select>
        <select wire:model.live="metalFilter" class="rj-select">
            <option value="">All metals</option>
            <option value="gold">Gold</option>
            <option value="silver">Silver</option>
            <option value="titanium">Titanium</option>
            <option value="platinum">Platinum</option>
        </select>
    </div>

    <x-ui.card class="!p-0 overflow-hidden">
        <x-ui.table :headers="['ID / HUID', 'Category', 'Metal', 'Purity', 'Weight', 'Packet', 'Status', '']">
            @foreach ($items as $item)
                <tr class="h-[60px] border-b border-line-light">
                    <td class="px-4 font-semibold text-gold">{{ $item->huid_code ?: $item->internal_code }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $item->category }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $item->metal ? ucfirst($item->metal) : '—' }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $item->purity }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $item->weight }}g</td>
                    <td class="px-4 text-ink_text-primary">{{ $item->packet?->code ?? '—' }}</td>
                    <td class="px-4">
                        <x-ui.badge :tone="$item->status === 'in_stock' ? 'success' : ($item->status === 'dispatched' ? 'warning' : 'neutral')">
                            {{ strtoupper(str_replace('_',' ',$item->status)) }}
                        </x-ui.badge>
                    </td>
                    <td class="px-4 text-right whitespace-nowrap">
                        <a href="{{ route('stock.items.show', $item) }}" class="text-gold font-semibold text-xs mr-3.5">View</a>
                        <button wire:click="edit({{ $item->id }})" class="bg-transparent border-0 text-gold font-semibold text-xs cursor-pointer">Edit</button>
                    </td>
                </tr>
            @endforeach
        </x-ui.table>
    </x-ui.card>

    <div class="mt-4">{{ $items->links() }}</div>
</div>
