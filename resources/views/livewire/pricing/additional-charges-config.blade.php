<div>
    <x-ui.page-header title="Additional Charges Config" subtitle="A simple list of named preset charges, with editable values." />

    <div class="bg-warning-bg text-warning rounded-control px-3.5 py-2.5 mb-5 max-w-[560px] text-xs">
        <code>sales.additional_charges</code> is a JSON column on each sale — there's no presets table to manage a reusable named list from yet. Fully editable in-memory below so the layout is evaluable; not persisted.
    </div>

    <x-ui.card class="max-w-[480px]">
        @foreach ($charges as $i => $c)
        <div class="flex items-center gap-2.5 py-2 border-b border-line-light">
            <span class="flex-1 text-sm font-semibold text-ink_text-primary">{{ $c['name'] }}</span>
            <span class="text-[12.5px] text-ink_text-secondary">₹</span>
            <input type="number" step="0.01" value="{{ $c['value'] }}" wire:change="updateValue({{ $i }}, $event.target.value)" class="rj-input w-[100px]">
            <button wire:click="remove({{ $i }})" class="bg-transparent border-0 text-danger text-xs cursor-pointer">Remove</button>
        </div>
        @endforeach

        <div class="flex gap-2 mt-4">
            <input type="text" wire:model="newName" placeholder="New charge name" class="rj-input flex-1">
            <input type="number" step="0.01" wire:model="newValue" placeholder="₹" class="rj-input w-[100px]">
            <x-ui.button type="button" variant="secondary" wire:click="add">Add</x-ui.button>
        </div>
        @error('newName') <div class="text-danger text-[11px] mt-1.5">{{ $message }}</div> @enderror
    </x-ui.card>
</div>
