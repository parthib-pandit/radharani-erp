<div>
    <x-ui.page-header title="Refinery Batch — Return" subtitle="Pick the outstanding batch, then enter refined weight and purity." />

    @if ($result)
        <div class="bg-success-bg text-success rounded-control px-3.5 py-2.5 mb-5 text-sm max-w-[480px]">{{ $result }}</div>
    @endif

    <x-ui.card class="max-w-[480px]">
        <form wire:submit="submit">
            <label class="block text-[11.5px] text-ink_text-secondary mb-1">Outstanding batch (sent, awaiting return)</label>
            @if ($outstandingBatches->isEmpty())
                <div class="text-[12.5px] text-ink_text-secondary border border-line rounded-control px-3 py-2.5 mb-3">No batches are currently outstanding.</div>
            @else
                <select wire:model="batchId" class="rj-select w-full mb-1.5">
                    @foreach ($outstandingBatches as $b)
                        <option value="{{ $b->id }}">#{{ $b->id }} — {{ $b->weight }}g sent {{ $b->sent_at?->diffForHumans() }}</option>
                    @endforeach
                </select>
            @endif
            @error('batchId') <div class="text-danger text-[11px] mb-2.5">Select a batch.</div> @enderror

            <label class="block text-[11.5px] text-ink_text-secondary mb-1">Refined weight (g)</label>
            <input type="number" step="0.001" wire:model="refinedWeight" class="rj-input w-full mb-1.5">
            @error('refinedWeight') <div class="text-danger text-[11px] mb-2.5">{{ $message }}</div> @enderror

            <label class="block text-[11.5px] text-ink_text-secondary mt-3.5 mb-1">Refined purity (%)</label>
            <input type="number" step="0.01" wire:model="refinedPurity" class="rj-input w-full mb-4.5">
            @error('refinedPurity') <div class="text-danger text-[11px] -mt-3.5 mb-3.5">{{ $message }}</div> @enderror

            <x-ui.button type="submit" variant="primary" class="w-full">Confirm Return</x-ui.button>
        </form>
    </x-ui.card>
</div>
