<x-layouts.app title="Loyalty Settings — Radharani Jewellery">
<div>
    <div class="rj-serif" style="font-size:24px;margin-bottom:4px;">Loyalty &amp; Referral Settings</div>
    <div style="font-size:13px;color:var(--muted);margin-bottom:24px;">Controls how points are earned and what they're worth — no code changes needed to adjust these.</div>

    @if (session('message'))
        <div class="rj-flash">{{ session('message') }}</div>
    @endif

    <div class="rj-card" style="max-width:520px;">
        <form wire:submit="save" style="display:flex;flex-direction:column;gap:16px;">
            <div>
                <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Points earned per ₹ spent</label>
                <div style="font-size:11px;color:var(--muted);margin-bottom:6px;">e.g. 0.001 = 1 point per ₹1,000</div>
                <input type="number" step="0.00001" wire:model="points_per_rupee" class="rj-input" style="width:200px;">
                @error('points_per_rupee') <div style="color:#B04A3C;font-size:11px;margin-top:3px;">{{ $message }}</div> @enderror
            </div>
            <div>
                <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Referral bonus (points)</label>
                <div style="font-size:11px;color:var(--muted);margin-bottom:6px;">Paid to the referrer once the new customer's first sale is confirmed</div>
                <input type="number" wire:model="referral_bonus_points" class="rj-input" style="width:200px;">
            </div>
            <div>
                <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Minimum points to redeem</label>
                <input type="number" wire:model="min_redeemable_points" class="rj-input" style="width:200px;">
            </div>
            <div>
                <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;">Value of 1 point (₹)</label>
                <div style="font-size:11px;color:var(--muted);margin-bottom:6px;">Used to show "your points are worth ₹X" to customers</div>
                <input type="number" step="0.01" wire:model="point_value_in_rupees" class="rj-input" style="width:200px;">
            </div>
            <div>
                <button type="submit" class="rj-btn-primary">Save Settings</button>
            </div>
        </form>
    </div>
</div>
</x-layouts.app>
