<div>
    <x-ui.page-header title="Scheme Enrolment" subtitle="Enrol a customer into the monthly installment scheme." />

    @if ($message)
        <div class="bg-success-bg text-success rounded-control px-3.5 py-2.5 mb-5 text-sm">{{ $message }}</div>
    @endif

    <x-ui.card class="max-w-[480px]">
        <div class="mb-3">
            <label class="block text-[11.5px] text-ink_text-secondary mb-1">Customer</label>
            @if ($this->customerObject)
                <div class="rj-input w-full flex justify-between items-center">
                    <span>{{ $this->customerObject->name }} ({{ $this->customerObject->phone }})</span>
                    <button type="button" wire:click="$set('customerId', null)" class="bg-transparent border-0 text-gold cursor-pointer text-xs">Change</button>
                </div>
            @else
                <input type="text" wire:model.live.debounce.300ms="customerSearch" placeholder="Search name or phone…" class="rj-input w-full">
                @if ($customerSearch)
                    <x-ui.card class="mt-1.5 !p-2">
                        @forelse ($customerResults as $c)
                            <div wire:click="pickCustomer({{ $c->id }})" class="py-1.5 px-1 cursor-pointer text-[13px] text-ink_text-primary hover:bg-surface-muted rounded">{{ $c->name }} — {{ $c->phone }}</div>
                        @empty
                            <div class="text-ink_text-secondary text-[13px] py-1.5 px-1">No matches.</div>
                        @endforelse
                    </x-ui.card>
                @endif
            @endif
            @error('customerId') <div class="text-danger text-[11px] mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="grid grid-cols-2 gap-3 mb-4">
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Monthly Amount (₹)</label>
                <input type="number" step="0.01" wire:model="monthlyAmount" class="rj-input w-full">
                @error('monthlyAmount') <div class="text-danger text-[11px] mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Start Date</label>
                <input type="date" wire:model="startDate" class="rj-input w-full">
                @error('startDate') <div class="text-danger text-[11px] mt-1">{{ $message }}</div> @enderror
            </div>
        </div>

        <x-ui.button type="button" wire:click="enrol" variant="primary">Enrol</x-ui.button>
    </x-ui.card>
</div>
