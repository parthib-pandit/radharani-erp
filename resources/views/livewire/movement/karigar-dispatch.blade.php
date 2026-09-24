<div>
    <x-ui.page-header title="Karigar Dispatch" subtitle="Choose what's being sent out — the form adapts to each case." />

    <div class="grid grid-cols-3 gap-3 mb-6 max-w-[820px]">
        <button wire:click="setSituation('tagged')" class="text-left cursor-pointer bg-white border rounded-card shadow-card p-5 {{ $situation === 'tagged' ? 'border-gold' : 'border-line' }}">
            <div class="font-bold text-[13.5px] text-ink_text-primary">Tagged Item Repair</div>
            <div class="text-[11.5px] text-ink_text-secondary mt-1">An existing, already-tagged item from stock.</div>
        </button>
        <button wire:click="setSituation('customer_material')" class="text-left cursor-pointer bg-white border rounded-card shadow-card p-5 {{ $situation === 'customer_material' ? 'border-gold' : 'border-line' }}">
            <div class="font-bold text-[13.5px] text-ink_text-primary">Customer's Own Material</div>
            <div class="text-[11.5px] text-ink_text-secondary mt-1">Untagged — never part of shop stock.</div>
        </button>
        <button wire:click="setSituation('raw_material')" class="text-left cursor-pointer bg-white border rounded-card shadow-card p-5 {{ $situation === 'raw_material' ? 'border-gold' : 'border-line' }}">
            <div class="font-bold text-[13.5px] text-ink_text-primary">Raw Material Issue</div>
            <div class="text-[11.5px] text-ink_text-secondary mt-1">No tagged item exists yet — just weight and metal.</div>
        </button>
    </div>

    @if ($result)
        <div class="bg-success-bg text-success rounded-control px-3.5 py-2.5 mb-5 text-sm max-w-[560px]">{{ $result }}</div>
    @endif

    <x-ui.card class="max-w-[560px]">

        @if ($situation === 'tagged')
        <div class="font-bold text-[13.5px] text-ink_text-primary mb-3.5">Send a tagged item for repair</div>
        <form wire:submit="dispatchTagged">
            <label class="block text-[11.5px] text-ink_text-secondary mb-1">Item</label>
            <input type="text" wire:model.live.debounce.300ms="itemSearch" placeholder="Search HUID / code / category..." class="rj-input w-full">
            @if ($itemSearch && $itemResults->isNotEmpty())
            <div class="border border-line rounded-control overflow-hidden mt-2">
                @foreach ($itemResults as $r)
                <div wire:click="$set('selectedItemId', {{ $r->id }})" class="px-3 py-2 text-[12.5px] cursor-pointer border-b border-line-light {{ $selectedItemId === $r->id ? 'bg-gold-soft/40' : 'bg-white' }}">
                    {{ $r->huid_code ?: $r->internal_code }} — {{ $r->category }}, {{ $r->weight }}g
                </div>
                @endforeach
            </div>
            @endif
            @error('selectedItemId') <div class="text-danger text-[11px] mt-1">Select an item.</div> @enderror

            <label class="block text-[11.5px] text-ink_text-secondary mt-3.5 mb-1">Karigar</label>
            <select wire:model="vendorId" class="rj-select w-full">
                <option value="">— select —</option>
                @foreach ($karigars as $k) <option value="{{ $k->id }}">{{ $k->name }}</option> @endforeach
            </select>
            @error('vendorId') <div class="text-danger text-[11px] mt-1">Select a karigar.</div> @enderror

            <label class="block text-[11.5px] text-ink_text-secondary mt-3.5 mb-1">Expected return date</label>
            <input type="date" wire:model="expectedReturn" class="rj-input w-full">

            <label class="block text-[11.5px] text-ink_text-secondary mt-3.5 mb-1">Note (optional)</label>
            <input type="text" wire:model="note" class="rj-input w-full mb-[18px]">

            <x-ui.button type="submit" variant="primary" class="w-full">Confirm Dispatch</x-ui.button>
        </form>
        @endif

        @if ($situation === 'customer_material')
        <div class="font-bold text-[13.5px] text-ink_text-primary mb-3.5">Dispatch a customer's own untagged material</div>
        <form wire:submit="dispatchCustomerMaterial">
            <label class="block text-[11.5px] text-ink_text-secondary mb-1">Customer</label>
            <input type="text" wire:model.live.debounce.300ms="customerSearch" placeholder="Search name / phone..." class="rj-input w-full">
            @if ($customerSearch && $customerResults->isNotEmpty())
            <div class="border border-line rounded-control overflow-hidden mt-2">
                @foreach ($customerResults as $c)
                <div wire:click="$set('selectedCustomerId', {{ $c->id }})" class="px-3 py-2 text-[12.5px] cursor-pointer border-b border-line-light {{ $selectedCustomerId === $c->id ? 'bg-gold-soft/40' : 'bg-white' }}">
                    {{ $c->name }} — {{ $c->phone }}
                </div>
                @endforeach
            </div>
            @endif
            @error('selectedCustomerId') <div class="text-danger text-[11px] mt-1">Select a customer.</div> @enderror

            <label class="block text-[11.5px] text-ink_text-secondary mt-3.5 mb-1">What is it</label>
            <input type="text" wire:model="description" placeholder="e.g. old gold chain, customer-supplied" class="rj-input w-full">
            @error('description') <div class="text-danger text-[11px] mt-1">{{ $message }}</div> @enderror

            <div class="grid grid-cols-2 gap-3 mt-3.5">
                <div>
                    <label class="block text-[11.5px] text-ink_text-secondary mb-1">Weight (g)</label>
                    <input type="number" step="0.001" wire:model="weight" class="rj-input w-full">
                    @error('weight') <div class="text-danger text-[11px] mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="block text-[11.5px] text-ink_text-secondary mb-1">Metal</label>
                    <select wire:model="metalType" class="rj-select w-full">
                        <option value="gold">Gold</option>
                        <option value="silver">Silver</option>
                        <option value="titanium">Titanium</option>
                        <option value="platinum">Platinum</option>
                    </select>
                </div>
            </div>
            <label class="block text-[11.5px] text-ink_text-secondary mt-3.5 mb-1">Karigar</label>
            <select wire:model="vendorId" class="rj-select w-full">
                <option value="">— select —</option>
                @foreach ($karigars as $k) <option value="{{ $k->id }}">{{ $k->name }}</option> @endforeach
            </select>
            @error('vendorId') <div class="text-danger text-[11px] mt-1">Select a karigar.</div> @enderror

            <label class="block text-[11.5px] text-ink_text-secondary mt-3.5 mb-1">Expected return date</label>
            <input type="date" wire:model="expectedReturn" class="rj-input w-full">

            <label class="block text-[11.5px] text-ink_text-secondary mt-3.5 mb-1">Note (optional)</label>
            <input type="text" wire:model="note" class="rj-input w-full mb-[18px]">

            <x-ui.button type="submit" variant="primary" class="w-full">Confirm Dispatch</x-ui.button>
        </form>
        @endif

        @if ($situation === 'raw_material')
        <div class="font-bold text-[13.5px] text-ink_text-primary mb-3.5">Issue raw material — no tagged item yet</div>
        <div class="text-xs text-ink_text-secondary mb-3.5">The finished piece won't have an identity until it comes back at Karigar Return.</div>
        <form wire:submit="dispatchRawMaterial">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11.5px] text-ink_text-secondary mb-1">Weight (g)</label>
                    <input type="number" step="0.001" wire:model="weight" class="rj-input w-full">
                    @error('weight') <div class="text-danger text-[11px] mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="block text-[11.5px] text-ink_text-secondary mb-1">Metal</label>
                    <select wire:model="metalType" class="rj-select w-full">
                        <option value="gold">Gold</option>
                        <option value="silver">Silver</option>
                        <option value="titanium">Titanium</option>
                        <option value="platinum">Platinum</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3 mt-3.5">
                <div>
                    <label class="block text-[11.5px] text-ink_text-secondary mb-1">Purity (optional)</label>
                    <input type="text" wire:model="purity" class="rj-input w-full">
                </div>
                <div>
                    <label class="block text-[11.5px] text-ink_text-secondary mb-1">Purpose (optional)</label>
                    <input type="text" wire:model="purposeLabel" class="rj-input w-full">
                </div>
            </div>
            <label class="block text-[11.5px] text-ink_text-secondary mt-3.5 mb-1">Karigar</label>
            <select wire:model="vendorId" class="rj-select w-full">
                <option value="">— select —</option>
                @foreach ($karigars as $k) <option value="{{ $k->id }}">{{ $k->name }}</option> @endforeach
            </select>
            @error('vendorId') <div class="text-danger text-[11px] mt-1">Select a karigar.</div> @enderror

            <label class="block text-[11.5px] text-ink_text-secondary mt-3.5 mb-1">Expected return date</label>
            <input type="date" wire:model="expectedReturn" class="rj-input w-full">

            <label class="block text-[11.5px] text-ink_text-secondary mt-3.5 mb-1">Note (optional)</label>
            <input type="text" wire:model="note" class="rj-input w-full mb-[18px]">

            <x-ui.button type="submit" variant="primary" class="w-full">Confirm Dispatch</x-ui.button>
        </form>
        @endif

    </x-ui.card>
</div>
