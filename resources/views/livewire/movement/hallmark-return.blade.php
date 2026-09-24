<div>
    <x-ui.page-header title="Hallmarking Return" subtitle="Record the HUID assigned, or generate an internal code if none was given." />

    @if ($result)
        <div class="bg-success-bg text-success rounded-control px-3.5 py-2.5 mb-5 text-sm max-w-[640px]">{{ $result }}</div>
    @endif

    <div class="grid grid-cols-[280px_1fr] gap-5 max-w-[900px]">
        <x-ui.card class="!p-0 overflow-hidden self-start">
            <div class="px-4 py-3 font-bold text-[12.5px] border-b border-line">Currently at hallmarking</div>
            @forelse ($openDispatches as $item)
                <div wire:click="selectItem({{ $item->id }})" class="px-4 py-2.5 text-[12.5px] cursor-pointer border-b border-line-light {{ $selectedItemId === $item->id ? 'bg-gold-soft/40' : 'bg-white' }}">
                    <div class="font-semibold text-ink_text-primary">{{ $item->huid_code ?: $item->internal_code }}</div>
                    <div class="text-ink_text-secondary text-[11px]">{{ $item->category }} · {{ $item->weight }}g</div>
                </div>
            @empty
                <div class="px-4 py-4 text-xs text-ink_text-secondary">Nothing currently at a hallmarking centre.</div>
            @endforelse
        </x-ui.card>

        <x-ui.card>
            @if (!$selectedItemId)
                <div class="text-sm text-ink_text-secondary">Select an item from the list on the left.</div>
            @else
            <form wire:submit="confirmReturn">
                <div class="flex items-center gap-2 mb-2.5">
                    <input type="checkbox" wire:model.live="generateInternal" id="geninternal" class="accent-gold">
                    <label for="geninternal" class="text-[12.5px] text-ink_text-primary">No HUID given — generate an internal code instead</label>
                </div>

                @if (!$generateInternal)
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">HUID number</label>
                <input type="text" wire:model="huidCode" class="rj-input w-full mb-1.5">
                @error('huidCode') <div class="text-danger text-[11px] mb-2.5">{{ $message }}</div> @enderror
                @else
                <div class="text-[11.5px] text-ink_text-secondary mb-3.5">A 7-character internal code will be generated on confirm.</div>
                @endif

                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Tagged by (name)</label>
                <input type="text" wire:model="taggedByName" placeholder="Staff member or hallmarking centre person" class="rj-input w-full mb-1.5">
                @error('taggedByName') <div class="text-danger text-[11px] mb-2.5">{{ $message }}</div> @enderror

                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Weight loss (g) *</label>
                <input type="number" step="0.001" wire:model="weightLoss" class="rj-input w-full mb-[18px]">
                @error('weightLoss') <div class="text-danger text-[11px] -mt-[14px] mb-[18px]">{{ $message }}</div> @enderror

                <x-ui.button type="submit" variant="primary" class="w-full">Confirm Return</x-ui.button>
            </form>
            @endif
        </x-ui.card>
    </div>
</div>
