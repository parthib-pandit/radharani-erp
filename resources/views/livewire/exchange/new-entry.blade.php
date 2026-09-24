<div>
    <x-ui.page-header title="Old Gold / Silver Exchange — New Entry" subtitle="A guided, one-way flow — each step locks in before the next opens." />

    <div class="flex gap-0 mb-7 max-w-[640px]">
        @foreach (['1'=>'Received','2'=>'Melted','3'=>'Tested','4'=>'Deduction','5'=>'Summary'] as $n => $label)
            <div wire:click="goToStep({{ $n }})" class="flex-1 text-center {{ $n <= $step + 1 ? 'cursor-pointer' : 'cursor-default' }}">
                <div class="w-[26px] h-[26px] rounded-full mx-auto mb-1.5 flex items-center justify-center text-xs font-bold
                    {{ $step >= $n ? 'bg-gold text-white' : 'bg-surface-muted text-ink_text-secondary' }}">{{ $n }}</div>
                <div class="text-[11px] {{ $step >= $n ? 'text-ink_text-primary font-bold' : 'text-ink_text-secondary font-semibold' }}">{{ $label }}</div>
            </div>
        @endforeach
    </div>

    <x-ui.card class="max-w-[520px]">

        @if ($step === 1)
        <div class="font-bold text-[13.5px] mb-3.5">Step 1 — As received from the customer</div>
        <label class="block text-[11.5px] text-ink_text-secondary mb-1">Customer</label>
        <input type="text" wire:model.live.debounce.300ms="customerSearch" placeholder="Search name / phone..." class="rj-input w-full">
        @if ($customerSearch && $customerResults->isNotEmpty())
        <div class="border border-line rounded-control overflow-hidden mt-2">
            @foreach ($customerResults as $c)
            <div wire:click="$set('customerId', {{ $c->id }})"
                class="px-3 py-2 text-[12.5px] cursor-pointer border-b border-line-light last:border-b-0 {{ $customerId===$c->id ? 'bg-gold-soft/40' : 'bg-white' }}">
                {{ $c->name }} — {{ $c->phone }}
            </div>
            @endforeach
        </div>
        @endif
        @error('customerId') <div class="text-danger text-[11px] mt-1">Select a customer.</div> @enderror

        <label class="block text-[11.5px] text-ink_text-secondary mt-3.5 mb-1">Gross weight, as received (g)</label>
        <input type="number" step="0.001" wire:model="grossWeight" class="rj-input w-full">
        @error('grossWeight') <div class="text-danger text-[11px] mt-1">{{ $message }}</div> @enderror

        <label class="block text-[11.5px] text-ink_text-secondary mt-3.5 mb-1">Description</label>
        <input type="text" wire:model="description" placeholder="e.g. broken chain, mixed studs" class="rj-input w-full mb-4.5">

        <x-ui.button wire:click="next" variant="primary" class="w-full">Continue to Melting</x-ui.button>
        @endif

        @if ($step === 2)
        <div class="font-bold text-[13.5px] mb-3.5">Step 2 — Net weight after melting</div>
        <div class="bg-surface-muted rounded-control px-3.5 py-2.5 mb-3.5 text-[12.5px]">Gross weight received: <strong>{{ $grossWeight }}g</strong></div>
        <label class="block text-[11.5px] text-ink_text-secondary mb-1">Net weight, after melting (g)</label>
        <input type="number" step="0.001" wire:model="netWeight" class="rj-input w-full mb-4.5">
        @error('netWeight') <div class="text-danger text-[11px] -mt-3.5 mb-3.5">{{ $message }}</div> @enderror
        <div class="flex gap-2.5">
            <x-ui.button wire:click="back" variant="secondary">Back</x-ui.button>
            <x-ui.button wire:click="next" variant="primary" class="flex-1">Continue to Testing</x-ui.button>
        </div>
        @endif

        @if ($step === 3)
        <div class="font-bold text-[13.5px] mb-3.5">Step 3 — Two independent purity tests</div>
        <div class="grid grid-cols-2 gap-3 mb-3">
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Test 1 (%)</label>
                <input type="number" step="0.01" wire:model.live="purityTest1" class="rj-input w-full">
            </div>
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Test 2 (%)</label>
                <input type="number" step="0.01" wire:model.live="purityTest2" class="rj-input w-full">
            </div>
        </div>
        <div class="bg-gold-soft/40 text-gold-dark rounded-control px-3.5 py-2.5 mb-4.5 text-[12.5px]">
            System-computed average: <strong>{{ $this->averagePurity }}%</strong>
        </div>
        @error('purityTest1') <div class="text-danger text-[11px] mb-2.5">Enter both readings.</div> @enderror
        <div class="flex gap-2.5">
            <x-ui.button wire:click="back" variant="secondary">Back</x-ui.button>
            <x-ui.button wire:click="next" variant="primary" class="flex-1">Continue to Deduction</x-ui.button>
        </div>
        @endif

        @if ($step === 4)
        <div class="font-bold text-[13.5px] mb-3.5">Step 4 — Shop's preset deduction</div>
        <div class="text-xs text-ink_text-secondary mb-3.5">Applied automatically — not typed per transaction.</div>
        <div class="bg-surface-muted rounded-control p-3.5 mb-4.5">
            <div class="flex justify-between text-[12.5px] mb-1.5"><span>Net weight</span><span>{{ $netWeight }}g</span></div>
            <div class="flex justify-between text-[12.5px] mb-1.5"><span>Preset deduction</span><span>{{ $presetDeductionPercent }}%</span></div>
            <div class="flex justify-between text-[13px] font-bold border-t border-line pt-2 mt-2"><span>Net payable weight</span><span>{{ $this->deductedWeight }}g</span></div>
        </div>
        <div class="flex gap-2.5">
            <x-ui.button wire:click="back" variant="secondary">Back</x-ui.button>
            <x-ui.button wire:click="next" variant="primary" class="flex-1">Compile Summary</x-ui.button>
        </div>
        @endif

        @if ($step === 5)
        <div class="font-bold text-[13.5px] mb-3.5">Summary — ready to send to accounts</div>
        <textarea readonly rows="10" class="w-full border border-line rounded-control p-3 text-[12.5px] font-mono bg-surface-bg">{{ $this->summaryText }}</textarea>
        <div class="flex gap-2.5 mt-3.5">
            <x-ui.button wire:click="back" variant="secondary">Back</x-ui.button>
            <x-ui.button variant="primary" class="flex-1" onclick="navigator.clipboard.writeText(document.querySelector('textarea').value)">Copy Summary for Accounts</x-ui.button>
        </div>
        @endif

    </x-ui.card>
</div>
