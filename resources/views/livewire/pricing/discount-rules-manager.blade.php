<div>
    <x-ui.page-header title="Discount Rules Manager" subtitle="Scope to an item, packet, box, category, or weight range." />

    @if (session('message'))<div class="bg-success-bg text-success rounded-control px-3.5 py-2.5 mb-5 text-sm">{{ session('message') }}</div>@endif

    <x-ui.card class="mb-6">
        <div class="font-bold text-sm text-ink_text-primary mb-3.5">{{ $editingId ? 'Edit Rule' : 'New Rule' }}</div>
        <form wire:submit="save" class="grid grid-cols-4 gap-3.5">
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Scope</label>
                <select wire:model.live="scope" class="rj-select w-full">
                    <option value="item">Item</option>
                    <option value="packet">Packet</option>
                    <option value="box">Box</option>
                    <option value="category">Category</option>
                    <option value="weight_tier">Weight range</option>
                </select>
            </div>

            @if ($scope === 'item')
            <div><label class="block text-[11.5px] text-ink_text-secondary mb-1">Item</label>
                <select wire:model="scopeRefId" class="rj-select w-full">
                    <option value="">— select —</option>
                    @foreach ($items as $i) <option value="{{ $i->id }}">{{ $i->huid_code ?: $i->internal_code }} — {{ $i->category }}</option> @endforeach
                </select>
            </div>
            @elseif ($scope === 'packet')
            <div><label class="block text-[11.5px] text-ink_text-secondary mb-1">Packet</label>
                <select wire:model="scopeRefId" class="rj-select w-full">
                    <option value="">— select —</option>
                    @foreach ($packets as $p) <option value="{{ $p->id }}">{{ $p->code }}</option> @endforeach
                </select>
            </div>
            @elseif ($scope === 'box')
            <div><label class="block text-[11.5px] text-ink_text-secondary mb-1">Box</label>
                <select wire:model="scopeRefId" class="rj-select w-full">
                    <option value="">— select —</option>
                    @foreach ($boxes as $b) <option value="{{ $b->id }}">{{ $b->code }}</option> @endforeach
                </select>
            </div>
            @elseif ($scope === 'category')
            <div><label class="block text-[11.5px] text-ink_text-secondary mb-1">Category</label>
                <input type="text" wire:model="category" class="rj-input w-full">
            </div>
            @else
            <div><label class="block text-[11.5px] text-ink_text-secondary mb-1">Min weight (g)</label>
                <input type="number" step="0.001" wire:model="minWeight" class="rj-input w-full">
            </div>
            <div><label class="block text-[11.5px] text-ink_text-secondary mb-1">Max weight (g)</label>
                <input type="number" step="0.001" wire:model="maxWeight" class="rj-input w-full">
            </div>
            @endif

            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Discount type</label>
                <select wire:model="discountType" class="rj-select w-full">
                    <option value="percentage">Percentage</option>
                    <option value="flat">Flat (₹)</option>
                </select>
            </div>
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Value</label>
                <input type="number" step="0.01" wire:model="value" class="rj-input w-full">
            </div>
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Valid from</label>
                <input type="date" wire:model="validFrom" class="rj-input w-full">
            </div>
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Valid to</label>
                <input type="date" wire:model="validTo" class="rj-input w-full">
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" wire:model="active" id="active" class="accent-gold">
                <label for="active" class="text-[12.5px] text-ink_text-primary">Active</label>
            </div>

            <div class="col-span-full flex gap-2.5">
                <x-ui.button type="submit" variant="primary">Save Rule</x-ui.button>
                @if ($editingId)<x-ui.button type="button" wire:click="cancel" variant="secondary">Cancel</x-ui.button>@endif
            </div>
        </form>
    </x-ui.card>

    <x-ui.card class="!p-0 overflow-hidden">
        <x-ui.table :headers="['Scope', 'Discount', 'Valid', 'Status', '']">
            @forelse ($rules as $r)
            <tr class="h-[60px] border-b border-line-light">
                <td class="px-4">
                    <span class="font-semibold text-ink_text-primary capitalize">{{ str_replace('_',' ',$r->scope) }}</span>
                    <span class="text-ink_text-secondary text-[11.5px]">
                        @if ($r->scope === 'category') — {{ $r->category }}
                        @elseif ($r->scope === 'weight_tier') — {{ $r->min_weight ?? '0' }}g–{{ $r->max_weight ?? '∞' }}g
                        @elseif ($r->scope_ref_id) — #{{ $r->scope_ref_id }}
                        @endif
                    </span>
                </td>
                <td class="px-4 text-ink_text-primary">{{ $r->discount_type === 'percentage' ? $r->value.'%' : '₹'.number_format($r->value,2) }}</td>
                <td class="px-4 text-[11.5px] text-ink_text-primary">{{ $r->valid_from?->format('d M Y') ?? 'any' }} – {{ $r->valid_to?->format('d M Y') ?? 'any' }}</td>
                <td class="px-4"><x-ui.badge :tone="$r->active ? 'success' : 'neutral'">{{ $r->active ? 'ACTIVE' : 'INACTIVE' }}</x-ui.badge></td>
                <td class="px-4 text-right">
                    <button wire:click="edit({{ $r->id }})" class="bg-transparent border-0 text-gold font-semibold text-xs cursor-pointer">Edit</button>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-5 py-4 text-ink_text-secondary">No discount rules yet.</td></tr>
            @endforelse
        </x-ui.table>
    </x-ui.card>
    <div class="mt-4">{{ $rules->links() }}</div>
</div>
