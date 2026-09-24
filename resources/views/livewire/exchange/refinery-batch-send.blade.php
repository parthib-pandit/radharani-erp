<div>
    <x-ui.page-header title="Refinery Batch — Send" subtitle="Old gold weight and a reference photo." />

    @if ($result)
        <div class="bg-success-bg text-success rounded-control px-3.5 py-2.5 mb-5 text-sm max-w-[480px]">{{ $result }}</div>
    @endif

    <x-ui.card class="max-w-[480px]">
        <form wire:submit="submit">
            <label class="block text-[11.5px] text-ink_text-secondary mb-1">Old gold weight (g)</label>
            <input type="number" step="0.001" wire:model="weight" class="rj-input w-full mb-1.5">
            @error('weight') <div class="text-danger text-[11px] mb-2.5">{{ $message }}</div> @enderror

            <label class="block text-[11.5px] text-ink_text-secondary mt-3.5 mb-1">Reference photo</label>
            <input type="file" wire:model="photo" accept="image/*" class="rj-input w-full">
            @error('photo') <div class="text-danger text-[11px] mt-1.5">{{ $message }}</div> @enderror
            @if ($photo)
                <img src="{{ $photo->temporaryUrl() }}" class="mt-2.5 w-[120px] h-[120px] object-cover rounded-control border border-line">
            @endif

            <x-ui.button type="submit" variant="primary" class="w-full mt-4.5" wire:loading.attr="disabled" wire:target="photo,submit">Confirm Send</x-ui.button>
        </form>
    </x-ui.card>
</div>
