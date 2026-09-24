<div>
    <x-ui.page-header title="Photography / Custom Purpose" subtitle="Any out-and-back trip that isn't a karigar or hallmarking visit." />

    <div class="flex gap-1 bg-surface-muted rounded-lg p-1 mb-5 max-w-[360px]">
        <button wire:click="setDirection('out')" class="flex-1 h-9 rounded-md text-[12.5px] font-bold cursor-pointer transition-colors {{ $direction === 'out' ? 'bg-white text-ink_text-primary shadow-sm' : 'bg-transparent text-ink_text-secondary' }}">Dispatch</button>
        <button wire:click="setDirection('in')" class="flex-1 h-9 rounded-md text-[12.5px] font-bold cursor-pointer transition-colors {{ $direction === 'in' ? 'bg-white text-ink_text-primary shadow-sm' : 'bg-transparent text-ink_text-secondary' }}">Return</button>
    </div>

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
                    {{ $r->huid_code ?: $r->internal_code }} — {{ $r->category }}
                </div>
                @endforeach
            </div>
            @endif
            @error('selectedItemId') <div class="text-danger text-[11px] mt-1">Select an item.</div> @enderror

            <label class="block text-[11.5px] text-ink_text-secondary mt-3.5 mb-1">Purpose</label>
            <input type="text" wire:model="purposeLabel" placeholder="e.g. Photography, Exhibition, Repair estimate" class="rj-input w-full mb-1.5">
            @error('purposeLabel') <div class="text-danger text-[11px] mb-3">{{ $message }}</div> @enderror

            <label class="block text-[11.5px] text-ink_text-secondary mt-3.5 mb-1">Upload Photo (optional)</label>
            <label class="flex flex-col items-center justify-center gap-1.5 border-2 border-dashed border-line rounded-control py-6 cursor-pointer hover:border-gold transition-colors">
                <x-ui.icon name="camera" :size="20" class="text-ink_text-muted" />
                <span class="text-[12.5px] font-semibold text-ink_text-primary">
                    {{ $photo ? $photo->getClientOriginalName() : 'Drop a file or tap to upload' }}
                </span>
                <span class="text-[11px] text-ink_text-muted">JPG or PNG, compressed automatically on save</span>
                <input type="file" wire:model="photo" accept="image/*" class="hidden">
            </label>
            @if ($photo)
                <img src="{{ $photo->temporaryUrl() }}" class="mt-2 h-24 rounded-control object-cover border border-line">
            @endif
            @error('photo') <div class="text-danger text-[11px] mt-1 mb-3">{{ $message }}</div> @enderror

            <x-ui.button type="submit" variant="primary" class="w-full mt-3">
                {{ $direction === 'out' ? 'Confirm Dispatch' : 'Confirm Return' }}
            </x-ui.button>
        </form>
    </x-ui.card>
</div>
