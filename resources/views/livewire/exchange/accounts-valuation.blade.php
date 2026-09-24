<div>
    <x-ui.page-header title="Accounts — Final Valuation Entry" subtitle="Pick a tested exchange and enter the final rupee value to settle it." />

    @if ($result)
        <div class="bg-success-bg text-success rounded-control px-3.5 py-2.5 mb-5 text-sm max-w-[480px]">{{ $result }}</div>
    @endif

    <x-ui.card class="max-w-[480px]">
        <form wire:submit="settle">
            <label class="block text-[11.5px] text-ink_text-secondary mb-1">Search by customer (optional)</label>
            <input type="text" wire:model.live.debounce.300ms="customerSearch" placeholder="Search name / phone..." class="rj-input w-full mb-3">

            <label class="block text-[11.5px] text-ink_text-secondary mb-1">Exchange ready for valuation (stage: tested)</label>
            @if ($readyTransactions->isEmpty())
                <div class="text-[12.5px] text-ink_text-secondary border border-line rounded-control px-3 py-2.5">No exchanges are currently in the "tested" stage.</div>
            @else
                <div class="border border-line rounded-control overflow-hidden">
                    @foreach ($readyTransactions as $t)
                    <div wire:click="selectTransaction({{ $t->id }})"
                        class="px-3 py-2 text-[12.5px] cursor-pointer border-b border-line-light last:border-b-0 {{ $transactionId===$t->id ? 'bg-gold-soft/40' : 'bg-white' }}">
                        #{{ $t->id }} — {{ $t->customer->name }} — {{ $t->customer->phone }} · deductable {{ $t->deductable_weight }}g · purity {{ $t->purity_averaged }}%
                    </div>
                    @endforeach
                </div>
            @endif
            @error('transactionId') <div class="text-danger text-[11px] mt-1">Select an exchange to settle.</div> @enderror

            <label class="block text-[11.5px] text-ink_text-secondary mt-3.5 mb-1">Final value (₹)</label>
            <input type="number" step="0.01" wire:model="finalValue" class="rj-input w-full mb-4.5">
            @error('finalValue') <div class="text-danger text-[11px] -mt-3.5 mb-3.5">{{ $message }}</div> @enderror

            <x-ui.button type="submit" variant="primary" class="w-full">Settle Exchange</x-ui.button>
        </form>
    </x-ui.card>
</div>
