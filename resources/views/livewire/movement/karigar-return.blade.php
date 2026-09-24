<div>
    <x-ui.page-header title="Karigar Return" subtitle="Record what comes back — weight loss is always entered by hand." />

    @if ($result)
        <div class="bg-success-bg text-success rounded-control px-3.5 py-2.5 mb-5 text-sm max-w-[640px]">{{ $result }}</div>
    @endif

    <div class="grid grid-cols-[280px_1fr] gap-5 max-w-[900px]">
        <x-ui.card class="!p-0 overflow-hidden self-start">
            <div class="px-4 py-3 font-bold text-[12.5px] border-b border-line">Currently with a karigar</div>
            @forelse ($openDispatches as $item)
                <div wire:click="selectItem({{ $item->id }})" class="px-4 py-2.5 text-[12.5px] cursor-pointer border-b border-line-light {{ $selectedItemId === $item->id ? 'bg-gold-soft/40' : 'bg-white' }}">
                    <div class="font-semibold text-ink_text-primary">{{ $item->huid_code ?: $item->internal_code }}</div>
                    <div class="text-ink_text-secondary text-[11px]">{{ $item->category }} · {{ $item->weight }}g</div>
                </div>
            @empty
                <div class="px-4 py-4 text-xs text-ink_text-secondary">Nothing currently out with a karigar.</div>
            @endforelse
        </x-ui.card>

        <x-ui.card>
            <div class="flex items-center gap-2 mb-4">
                <input type="checkbox" wire:model.live="isRawMaterialReturn" id="rawret" class="accent-gold">
                <label for="rawret" class="text-[12.5px] text-ink_text-primary">This return is for a raw-material dispatch (no existing item yet)</label>
            </div>

            @if ($isRawMaterialReturn)
            <div class="bg-warning-bg text-warning rounded-control px-3.5 py-2.5 mb-4 text-xs">
                The finished piece has no identity yet — one will be created here and held as <strong>pending review</strong> until admin confirms it into stock. Only one new item per return is supported right now; a raw-material dispatch returning as several pieces isn't built yet (still an open question with the client).
            </div>
            <form wire:submit="confirmReturn">
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Raw-material batch</label>
                <select wire:model="selectedBatchId" class="rj-select w-full mb-1.5">
                    <option value="">— select —</option>
                    @foreach ($openRawBatches as $batch)
                    <option value="{{ $batch->id }}">#{{ $batch->id }} — {{ $batch->vendor->name }}, {{ $batch->weight_out }}g {{ $batch->metal }}</option>
                    @endforeach
                </select>
                @error('selectedBatchId') <div class="text-danger text-[11px] mb-2.5">{{ $message }}</div> @enderror

                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div>
                        <label class="block text-[11.5px] text-ink_text-secondary mb-1">Category</label>
                        <input type="text" wire:model="newCategory" class="rj-input w-full">
                        @error('newCategory') <div class="text-danger text-[11px] mt-0.5">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="block text-[11.5px] text-ink_text-secondary mb-1">Purity</label>
                        <input type="text" wire:model="newPurity" class="rj-input w-full">
                        @error('newPurity') <div class="text-danger text-[11px] mt-0.5">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="block text-[11.5px] text-ink_text-secondary mb-1">Net weight (g)</label>
                        <input type="number" step="0.001" wire:model="newWeight" class="rj-input w-full">
                        @error('newWeight') <div class="text-danger text-[11px] mt-0.5">{{ $message }}</div> @enderror
                    </div>
                    <div><label class="block text-[11.5px] text-ink_text-secondary mb-1">Description</label><input type="text" wire:model="newDescription" class="rj-input w-full"></div>
                </div>

                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Actual return date</label>
                <input type="date" wire:model="actualReturn" class="rj-input w-full mb-4">

                <x-ui.badge tone="warning" class="mb-4">WILL ENTER: PENDING REVIEW</x-ui.badge>
                <x-ui.button type="submit" variant="primary" class="w-full block">Create Item &amp; Confirm Return</x-ui.button>
            </form>
            @else

            @if (!$selectedItemId)
                <div class="text-sm text-ink_text-secondary">Select an item from the list on the left.</div>
            @else
            <form wire:submit="confirmReturn">
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div>
                        <label class="block text-[11.5px] text-ink_text-secondary mb-1">Weight loss / wastage (g) *</label>
                        <input type="number" step="0.001" wire:model="weightLoss" class="rj-input w-full">
                        @error('weightLoss') <div class="text-danger text-[11px] mt-0.5">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="block text-[11.5px] text-ink_text-secondary mb-1">Actual return date</label>
                        <input type="date" wire:model="actualReturn" class="rj-input w-full">
                    </div>
                </div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Note (optional)</label>
                <input type="text" wire:model="note" class="rj-input w-full mb-4">

                <div class="flex items-center gap-2 mb-1.5">
                    <input type="checkbox" wire:model="needsHallmarking" id="needshm" class="accent-gold">
                    <label for="needshm" class="text-[12.5px] text-ink_text-primary">Does this need to go to hallmarking next?</label>
                </div>
                @if ($needsHallmarking)
                <div class="bg-warning-bg text-warning rounded-control px-3 py-2 mb-4 text-[11.5px]">
                    → Will chain straight into a Hallmarking Dispatch movement on confirm.
                </div>
                @endif

                <x-ui.button type="submit" variant="primary" class="w-full">Confirm Return</x-ui.button>
            </form>
            @endif
            @endif
        </x-ui.card>
    </div>
</div>
