<div>
    <x-ui.page-header title="Making-Charge Configuration" subtitle="Three charge types — only the relevant value field shows for the one selected." />

    <div class="bg-warning-bg text-warning rounded-control px-3.5 py-2.5 mb-5 max-w-[640px] text-xs">
        Two flags here: <code>items.making_type</code> only supports per_piece/percentage — "flat per gram" has nowhere to be stored yet — and there's no table for category-level presets at all (only per-item values, already covered under Stock → Add/Edit Item). Sample presets shown below; the form doesn't persist yet.
    </div>

    @if ($result)<div class="bg-success-bg text-success rounded-control px-3.5 py-2.5 mb-5 max-w-[480px] text-sm">{{ $result }}</div>@endif

    <x-ui.card class="max-w-[480px] mb-6">
        <form wire:submit="save">
            <label class="block text-[11.5px] text-ink_text-secondary mb-1">Category</label>
            <input type="text" wire:model="category" class="rj-input w-full mb-3.5">
            @error('category') <div class="text-danger text-[11px] -mt-2 mb-3">{{ $message }}</div> @enderror

            <label class="block text-[11.5px] text-ink_text-secondary mb-1.5">Charge type</label>
            <div class="flex gap-1 bg-surface-muted rounded-lg p-1 mb-4">
                <button type="button" wire:click="$set('selectedType','percentage')" class="flex-1 h-[34px] border-0 rounded-md text-xs font-bold cursor-pointer {{ $selectedType==='percentage' ? 'bg-white text-ink_text-primary' : 'bg-transparent text-ink_text-secondary' }}">% of metal value</button>
                <button type="button" wire:click="$set('selectedType','per_piece')" class="flex-1 h-[34px] border-0 rounded-md text-xs font-bold cursor-pointer {{ $selectedType==='per_piece' ? 'bg-white text-ink_text-primary' : 'bg-transparent text-ink_text-secondary' }}">Flat / piece</button>
                <button type="button" wire:click="$set('selectedType','per_gram')" class="flex-1 h-[34px] border-0 rounded-md text-xs font-bold cursor-pointer {{ $selectedType==='per_gram' ? 'bg-white text-ink_text-primary' : 'bg-transparent text-ink_text-secondary' }}">Flat / gram</button>
            </div>

            <label class="block text-[11.5px] text-ink_text-secondary mb-1">
                @if ($selectedType==='percentage') Percentage (%) @elseif ($selectedType==='per_piece') Amount per piece (₹) @else Amount per gram (₹) @endif
            </label>
            <input type="number" step="0.01" wire:model="value" class="rj-input w-full mb-4.5">
            @error('value') <div class="text-danger text-[11px] -mt-3.5 mb-3.5">{{ $message }}</div> @enderror

            <x-ui.button type="submit" variant="primary" class="w-full">Save Preset</x-ui.button>
        </form>
    </x-ui.card>

    <x-ui.card class="!p-0 overflow-hidden max-w-[480px]">
        <div class="px-4 py-3 font-bold text-[12.5px] border-b border-line text-ink_text-primary">Existing Presets (sample)</div>
        @foreach ($presets as $p)
        <div class="flex justify-between px-4 py-2.5 text-[12.5px] border-b border-line-light">
            <span class="text-ink_text-primary">{{ $p['category'] }}</span>
            <span class="text-ink_text-secondary">
                @if ($p['type']==='percentage') {{ $p['value'] }}% @elseif ($p['type']==='per_piece') ₹{{ $p['value'] }}/pc @else ₹{{ $p['value'] }}/g @endif
            </span>
        </div>
        @endforeach
    </x-ui.card>
</div>
