<div>
    <x-ui.page-header title="New Purchase Entry" subtitle="Admin only. Record a purchase from a karigar or supplier." />

    @if ($savedMessage)
        <div class="bg-success-bg text-success rounded-control px-3.5 py-2.5 mb-5 text-sm">{{ $savedMessage }}</div>
    @endif

    <x-ui.card class="max-w-[960px]">
        <div class="flex gap-2 mb-4">
            <x-ui.button type="button" :variant="$purchaseType === 'finished_product' ? 'primary' : 'secondary'" wire:click="$set('purchaseType', 'finished_product')">Finished Product</x-ui.button>
            <x-ui.button type="button" :variant="$purchaseType === 'raw_material' ? 'primary' : 'secondary'" wire:click="$set('purchaseType', 'raw_material')">Raw Material</x-ui.button>
        </div>

        <form wire:submit="save">
            <div class="grid grid-cols-2 gap-3 mb-3">
                <div>
                    <label class="block text-[11.5px] text-ink_text-secondary mb-1">Vendor</label>
                    <select class="rj-select w-full" wire:model="vendorId">
                        <option value="">Select vendor…</option>
                        @foreach ($vendors as $v)
                            <option value="{{ $v->id }}">{{ $v->name }} ({{ str($v->type)->replace('_',' ')->title() }})</option>
                        @endforeach
                    </select>
                    @error('vendorId') <div class="text-danger text-[11px] mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="block text-[11.5px] text-ink_text-secondary mb-1">Invoice / Bill No. (optional)</label>
                    <input type="text" class="rj-input w-full" wire:model="invoiceNumber">
                </div>
                <div>
                    <label class="block text-[11.5px] text-ink_text-secondary mb-1">GST (₹, optional)</label>
                    <input type="number" step="0.01" class="rj-input w-full" wire:model="gst">
                </div>
                <div>
                    <label class="block text-[11.5px] text-ink_text-secondary mb-1">Payment Status</label>
                    <select class="rj-select w-full" wire:model="paymentStatus">
                        <option value="pending">Pending</option>
                        <option value="partial">Partial</option>
                        <option value="paid">Paid</option>
                    </select>
                </div>
            </div>

            @if ($purchaseType === 'raw_material')
                <div class="bg-gold-soft/40 border border-line rounded-control p-3 mb-3 text-sm text-gold-dark">
                    Raw material arrives untagged. Add a description line per lot below — each is saved as a
                    "pending tag" purchase line with no item yet. Convert them into real items from the
                    "Pending Tags" section on the Stock &gt; Items screen.
                </div>
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div>
                        <label class="block text-[11.5px] text-ink_text-secondary mb-1">Total Weight (g)</label>
                        <input type="number" step="0.001" class="rj-input w-full" wire:model="totalWeight">
                        @error('totalWeight') <div class="text-danger text-[11px] mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="block text-[11.5px] text-ink_text-secondary mb-1">Total Amount (₹)</label>
                        <input type="number" step="0.01" class="rj-input w-full" wire:model="totalAmount">
                        @error('totalAmount') <div class="text-danger text-[11px] mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="font-semibold text-sm text-ink_text-primary mt-4 mb-2">Lines (pending tag)</div>
                @foreach ($rawLines as $i => $line)
                    <div class="grid grid-cols-[2fr_1fr_1fr_1fr_1fr_1fr_auto] gap-2 items-end mb-2">
                        <div>
                            <label class="block text-[11px] text-ink_text-secondary mb-1">Description</label>
                            <input type="text" class="rj-input w-full" wire:model="rawLines.{{ $i }}.description">
                        </div>
                        <div>
                            <label class="block text-[11px] text-ink_text-secondary mb-1">Category</label>
                            <input type="text" class="rj-input w-full" wire:model="rawLines.{{ $i }}.category">
                        </div>
                        <div>
                            <label class="block text-[11px] text-ink_text-secondary mb-1">Metal</label>
                            <select class="rj-select w-full" wire:model="rawLines.{{ $i }}.metal">
                                <option value="gold">Gold</option>
                                <option value="silver">Silver</option>
                                <option value="titanium">Titanium</option>
                                <option value="platinum">Platinum</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] text-ink_text-secondary mb-1">Purity</label>
                            <input type="text" class="rj-input w-full" placeholder="22K / 92.5" wire:model="rawLines.{{ $i }}.purity">
                        </div>
                        <div>
                            <label class="block text-[11px] text-ink_text-secondary mb-1">Weight (g)</label>
                            <input type="number" step="0.001" class="rj-input w-full" wire:model="rawLines.{{ $i }}.weight">
                        </div>
                        <div>
                            <label class="block text-[11px] text-ink_text-secondary mb-1">Rate (₹/g)</label>
                            <input type="number" step="0.01" class="rj-input w-full" wire:model="rawLines.{{ $i }}.rate">
                        </div>
                        <x-ui.button type="button" variant="secondary" wire:click="removeRawLine({{ $i }})">Remove</x-ui.button>
                    </div>
                @endforeach
                <x-ui.button type="button" variant="secondary" wire:click="addBlankRawLine" class="mb-4">+ Add Line</x-ui.button>
            @else
                <div class="mb-3">
                    <label class="block text-[11.5px] text-ink_text-secondary mb-1">Total Amount (₹)</label>
                    <input type="number" step="0.01" class="rj-input max-w-[220px]" wire:model="totalAmount">
                    @error('totalAmount') <div class="text-danger text-[11px] mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="font-semibold text-sm text-ink_text-primary mt-4 mb-2">Items</div>
                <div class="mb-2.5">
                    <input type="text" class="rj-input max-w-[340px]" placeholder="Search HUID or internal code to add an item…" wire:model.live.debounce.300ms="itemSearch">
                    @if (strlen($itemSearch) >= 2)
                        <x-ui.card class="mt-1.5 max-w-[340px] !p-2">
                            @forelse ($this->searchResults as $result)
                                <div class="px-1 py-1.5 cursor-pointer text-sm" wire:click="pickItem({{ count($lines) - 1 }}, {{ $result->id }})">
                                    {{ $result->huid_code ?: $result->internal_code }} — {{ $result->category }}, {{ $result->weight }}g
                                </div>
                            @empty
                                <div class="text-ink_text-secondary text-sm px-1 py-1.5">No matching items.</div>
                            @endforelse
                        </x-ui.card>
                    @endif
                </div>

                @foreach ($lines as $i => $line)
                    <div class="grid grid-cols-[2fr_1fr_1fr_auto] gap-2 items-end mb-2">
                        <div>
                            <label class="block text-[11px] text-ink_text-secondary mb-1">Item</label>
                            <div class="rj-input bg-surface-muted">{{ $line['label'] ?: 'Not selected' }}</div>
                        </div>
                        <div>
                            <label class="block text-[11px] text-ink_text-secondary mb-1">Rate (₹/g)</label>
                            <input type="number" step="0.01" class="rj-input w-full" wire:model="lines.{{ $i }}.rate">
                        </div>
                        <div>
                            <label class="block text-[11px] text-ink_text-secondary mb-1">Weight (g)</label>
                            <input type="number" step="0.001" class="rj-input w-full" wire:model="lines.{{ $i }}.weight">
                        </div>
                        <x-ui.button type="button" variant="secondary" wire:click="removeLine({{ $i }})">Remove</x-ui.button>
                    </div>
                @endforeach
                <x-ui.button type="button" variant="secondary" wire:click="addBlankLine" class="mb-4">+ Add Line</x-ui.button>
            @endif

            <div>
                <x-ui.button type="submit" variant="primary">Save Purchase</x-ui.button>
            </div>
        </form>
    </x-ui.card>
</div>
