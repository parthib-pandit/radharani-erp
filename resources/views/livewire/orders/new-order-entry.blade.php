<div>
    <x-ui.page-header title="New Custom Order" subtitle="Note which of the two rate rules applies before the customer leaves." />

    @if ($result)<div class="bg-success-bg text-success rounded-control px-3.5 py-2.5 mb-5 max-w-[560px] text-sm">{{ $result }}</div>@endif

    <x-ui.card class="max-w-[560px]">
        <form wire:submit="submit">
            <label class="block text-[11.5px] text-ink_text-secondary mb-1">Customer</label>
            <input type="text" wire:model.live.debounce.300ms="customerSearch" placeholder="Search name / phone..." class="rj-input w-full">
            @if ($customerSearch && $customerResults->isNotEmpty())
            <div class="border border-line rounded-lg overflow-hidden mt-2">
                @foreach ($customerResults as $c)
                <div wire:click="$set('customerId', {{ $c->id }})" class="px-3 py-2 text-[12.5px] cursor-pointer border-b border-line-light {{ $customerId === $c->id ? 'bg-gold-soft/40' : 'bg-white' }}">
                    {{ $c->name }} — {{ $c->phone }}
                </div>
                @endforeach
            </div>
            @endif
            @error('customerId') <div class="text-danger text-[11px] mt-1">Select a customer.</div> @enderror

            <label class="block text-[11.5px] text-ink_text-secondary mt-3.5 mb-1">What's being ordered</label>
            <input type="text" wire:model="productDescription" placeholder="e.g. 22K bridal necklace set, custom design" class="rj-input w-full">
            @error('productDescription') <div class="text-danger text-[11px] mt-1">{{ $message }}</div> @enderror

            <div class="grid grid-cols-2 gap-3 mt-3.5">
                <div>
                    <label class="block text-[11.5px] text-ink_text-secondary mb-1">Category</label>
                    <input type="text" wire:model="category" placeholder="e.g. Necklace, Bangles" class="rj-input w-full">
                </div>
                <div>
                    <label class="block text-[11.5px] text-ink_text-secondary mb-1">Metal</label>
                    <select wire:model="metal" class="rj-input w-full">
                        <option value="">Select metal</option>
                        <option value="gold">Gold</option>
                        <option value="silver">Silver</option>
                        <option value="titanium">Titanium</option>
                        <option value="platinum">Platinum</option>
                    </select>
                    @error('metal') <div class="text-danger text-[11px] mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="flex items-center gap-2 my-3.5">
                <input type="checkbox" wire:model.live="inStock" id="instock" class="accent-gold">
                <label for="instock" class="text-[12.5px] text-ink_text-primary">Product is currently in stock</label>
            </div>
            @if ($inStock)
            <input type="text" wire:model.live.debounce.300ms="existingItemSearch" placeholder="Search existing item HUID / code..." class="rj-input w-full">
            @if ($existingItemSearch && $itemResults->isNotEmpty())
            <div class="border border-line rounded-lg overflow-hidden mt-2 mb-3.5">
                @foreach ($itemResults as $it)
                <div wire:click="$set('existingItemId', {{ $it->id }})" class="px-3 py-2 text-[12.5px] cursor-pointer border-b border-line-light {{ $existingItemId === $it->id ? 'bg-gold-soft/40' : 'bg-white' }}">
                    {{ $it->huid_code ?? $it->internal_code }} — {{ $it->category }} ({{ $it->weight }}g)
                </div>
                @endforeach
            </div>
            @else
            <div class="mb-3.5"></div>
            @endif
            @else
            <div class="text-[11.5px] text-ink_text-secondary mb-3.5">Needs to be made — this becomes a Karigar Dispatch (Raw Material Issue) once confirmed.</div>
            @endif

            <label class="block text-[11.5px] text-ink_text-secondary mb-1">Estimated value (₹)</label>
            <input type="number" step="0.01" wire:model="estimatedValue" class="rj-input w-full">
            @error('estimatedValue') <div class="text-danger text-[11px] mt-1">{{ $message }}</div> @enderror

            <div class="mt-4.5 p-3.5 rounded-control {{ $fullPaymentNow ? 'bg-success-bg' : 'bg-surface-muted' }}">
                <div class="flex items-center gap-2 mb-2.5">
                    <input type="checkbox" wire:model.live="fullPaymentNow" id="fullpay" class="accent-gold">
                    <label for="fullpay" class="text-[12.5px] font-bold text-ink_text-primary">Customer is paying the full value now</label>
                </div>
                @if ($fullPaymentNow)
                    <div class="text-xs text-success font-bold">→ RATE LOCKED to today's rate for this order.</div>
                @else
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Advance / deposit amount (₹)</label>
                <input type="number" step="0.01" wire:model="depositAmount" class="rj-input w-full mb-2">
                <div class="text-xs text-warning font-bold">→ Rate applies AT DELIVERY, not today.</div>
                @endif
            </div>

            <x-ui.button type="submit" variant="primary" class="w-full mt-4.5">Create Order</x-ui.button>
        </form>
    </x-ui.card>
</div>
