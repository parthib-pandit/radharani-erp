<div>
    <x-ui.page-header title="Daily Rate Entry" subtitle="Deliberately minimal — today's rates, and when they were last touched." />

    @if ($result)<div class="bg-success-bg text-success rounded-control px-3.5 py-2.5 mb-5 max-w-[420px] text-sm">{{ $result }}</div>@endif

    <x-ui.card class="max-w-[420px]">
        <form wire:submit="save">
            <label class="block text-[11.5px] text-ink_text-secondary mb-1">Gold rate (₹/g)</label>
            <input type="number" step="0.01" wire:model="rates.gold" class="rj-input w-full">
            <div class="text-[11px] text-ink_text-secondary mt-1 mb-3.5">
                Last updated: {{ $last['gold']?->created_at?->diffForHumans() ?? 'never' }}
            </div>
            @error('rates.gold') <div class="text-danger text-[11px] -mt-2 mb-3">{{ $message }}</div> @enderror

            <label class="block text-[11.5px] text-ink_text-secondary mb-1">Silver rate (₹/g)</label>
            <input type="number" step="0.01" wire:model="rates.silver" class="rj-input w-full">
            <div class="text-[11px] text-ink_text-secondary mt-1 mb-3.5">
                Last updated: {{ $last['silver']?->created_at?->diffForHumans() ?? 'never' }}
            </div>
            @error('rates.silver') <div class="text-danger text-[11px] -mt-2 mb-3">{{ $message }}</div> @enderror

            <label class="block text-[11.5px] text-ink_text-secondary mb-1">Titanium rate (₹/g)</label>
            <input type="number" step="0.01" wire:model="rates.titanium" class="rj-input w-full">
            <div class="text-[11px] text-ink_text-secondary mt-1 mb-3.5">
                Last updated: {{ $last['titanium']?->created_at?->diffForHumans() ?? 'never' }}
            </div>
            @error('rates.titanium') <div class="text-danger text-[11px] -mt-2 mb-3">{{ $message }}</div> @enderror

            <label class="block text-[11.5px] text-ink_text-secondary mb-1">Platinum rate (₹/g)</label>
            <input type="number" step="0.01" wire:model="rates.platinum" class="rj-input w-full">
            <div class="text-[11px] text-ink_text-secondary mt-1 mb-4.5">
                Last updated: {{ $last['platinum']?->created_at?->diffForHumans() ?? 'never' }}
            </div>
            @error('rates.platinum') <div class="text-danger text-[11px] -mt-3.5 mb-3.5">{{ $message }}</div> @enderror

            <x-ui.button type="submit" variant="primary" class="w-full">Save Today's Rates</x-ui.button>
        </form>
    </x-ui.card>
</div>
