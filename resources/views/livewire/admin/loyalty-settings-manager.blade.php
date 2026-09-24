<div>
    <x-ui.page-header title="Loyalty & Referral Settings" subtitle="Controls how points are earned and what they're worth — no code changes needed to adjust these." />

    @if (session('message'))
        <div class="bg-success-bg text-success rounded-control px-3.5 py-2.5 mb-5 text-sm">{{ session('message') }}</div>
    @endif

    <x-ui.card class="max-w-[520px]">
        <form wire:submit="save" class="flex flex-col gap-4">
            <div>
                <label class="block text-xs font-semibold text-ink_text-primary mb-1">Points earned per ₹ spent</label>
                <div class="text-[11px] text-ink_text-secondary mb-1.5">e.g. 0.001 = 1 point per ₹1,000</div>
                <input type="number" step="0.00001" wire:model="points_per_rupee" class="rj-input w-[200px]">
                @error('points_per_rupee') <div class="text-danger text-[11px] mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-ink_text-primary mb-1">Referral bonus (points)</label>
                <div class="text-[11px] text-ink_text-secondary mb-1.5">Paid to the referrer once the new customer's first sale is confirmed</div>
                <input type="number" wire:model="referral_bonus_points" class="rj-input w-[200px]">
            </div>
            <div>
                <label class="block text-xs font-semibold text-ink_text-primary mb-1">Minimum points to redeem</label>
                <input type="number" wire:model="min_redeemable_points" class="rj-input w-[200px]">
            </div>
            <div>
                <label class="block text-xs font-semibold text-ink_text-primary mb-1">Value of 1 point (₹)</label>
                <div class="text-[11px] text-ink_text-secondary mb-1.5">Used to show "your points are worth ₹X" to customers</div>
                <input type="number" step="0.01" wire:model="point_value_in_rupees" class="rj-input w-[200px]">
            </div>
            <div>
                <x-ui.button type="submit" variant="primary">Save Settings</x-ui.button>
            </div>
        </form>
    </x-ui.card>
</div>
