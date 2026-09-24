<div>
    <x-ui.page-header title="Hallmarking Dispatch" subtitle="Send an item to a hallmarking centre." />

    @if ($result)
        <div class="bg-success-bg text-success rounded-control px-3.5 py-2.5 mb-5 text-sm max-w-[480px]">{{ $result }}</div>
    @endif

    <x-ui.card class="max-w-[480px]">
        <form wire:submit="submit">
            <label class="block text-[11.5px] text-ink_text-secondary mb-1">Item</label>
            <input type="text" wire:model.live.debounce.300ms="itemSearch" placeholder="Search HUID / code..." class="rj-input w-full">
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

            <label class="block text-[11.5px] text-ink_text-secondary mt-3.5 mb-1">Hallmarking centre</label>
            <select wire:model="centreId" class="rj-select w-full">
                <option value="">— select —</option>
                @foreach ($centres as $c) <option value="{{ $c->id }}">{{ $c->name }}</option> @endforeach
            </select>
            @error('centreId') <div class="text-danger text-[11px] mt-1">Select a centre.</div> @enderror

            <label class="block text-[11.5px] text-ink_text-secondary mt-3.5 mb-1">Expected return date</label>
            <input type="date" wire:model="expectedReturn" class="rj-input w-full mb-[18px]">

            <x-ui.button type="submit" variant="primary" class="w-full">Confirm Dispatch</x-ui.button>
        </form>
    </x-ui.card>
</div>
