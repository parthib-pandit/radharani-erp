<x-layouts.app title="Customers — Radharani Jewellery">
<div>
    <div class="rj-serif" style="font-size:24px;margin-bottom:4px;">Customers</div>
    <div style="font-size:13px;color:var(--muted);margin-bottom:24px;">Directory, plus portal password assignment — customers cannot self-register.</div>

    @if (session('message'))
        <div class="rj-flash">{{ session('message') }}</div>
    @endif

    <div class="rj-card" style="margin-bottom:24px;">
        <div style="font-weight:700;font-size:14px;margin-bottom:14px;">{{ $editingId ? 'Edit Customer' : 'New Customer' }}</div>
        <form wire:submit="save" style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;">
            <div>
                <label style="display:block;font-size:11.5px;color:var(--muted);margin-bottom:4px;">Name</label>
                <input type="text" wire:model="name" class="rj-input" style="width:100%;">
                @error('name') <div style="color:#B04A3C;font-size:11px;margin-top:3px;">{{ $message }}</div> @enderror
            </div>
            <div>
                <label style="display:block;font-size:11.5px;color:var(--muted);margin-bottom:4px;">Phone</label>
                <input type="text" wire:model="phone" class="rj-input" style="width:100%;">
                @error('phone') <div style="color:#B04A3C;font-size:11px;margin-top:3px;">{{ $message }}</div> @enderror
            </div>
            <div>
                <label style="display:block;font-size:11.5px;color:var(--muted);margin-bottom:4px;">Email</label>
                <input type="email" wire:model="email" class="rj-input" style="width:100%;">
            </div>
            <div style="grid-column:span 2;">
                <label style="display:block;font-size:11.5px;color:var(--muted);margin-bottom:4px;">Address</label>
                <input type="text" wire:model="address" class="rj-input" style="width:100%;">
            </div>
            <div>
                <label style="display:block;font-size:11.5px;color:var(--muted);margin-bottom:4px;">GSTIN (optional)</label>
                <input type="text" wire:model="gstin" class="rj-input" style="width:100%;">
            </div>
            <div>
                <label style="display:block;font-size:11.5px;color:var(--muted);margin-bottom:4px;">Status</label>
                <select wire:model="status" class="rj-select" style="width:100%;">
                    <option value="past_customer">Past Customer</option>
                    <option value="order_given">Order Given</option>
                    <option value="order_pending">Order Pending</option>
                </select>
            </div>
            <div style="grid-column:1/-1;display:flex;gap:10px;">
                <button type="submit" class="rj-btn-primary">Save</button>
                @if ($editingId)
                    <button type="button" wire:click="cancel" class="rj-btn-secondary">Cancel</button>
                @endif
            </div>
        </form>
    </div>

    @if ($showPasswordFor)
        <div class="rj-card" style="margin-bottom:24px;max-width:400px;">
            <div style="font-weight:700;font-size:14px;margin-bottom:10px;">Set Portal Password</div>
            <div style="font-size:12px;color:var(--muted);margin-bottom:12px;">Share this password with the customer directly — there is no reset-by-email flow yet.</div>
            <input type="text" wire:model="newPassword" class="rj-input" style="width:100%;margin-bottom:10px;" placeholder="New password (min 8 characters)">
            @error('newPassword') <div style="color:#B04A3C;font-size:11px;margin-bottom:8px;">{{ $message }}</div> @enderror
            <div style="display:flex;gap:10px;">
                <button wire:click="setPassword" class="rj-btn-primary">Set Password</button>
                <button wire:click="$set('showPasswordFor', false)" class="rj-btn-secondary">Cancel</button>
            </div>
        </div>
    @endif

    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search customers..."
        class="rj-input" style="margin-bottom:14px;width:280px;">

    <div class="rj-card" style="padding:0;overflow:hidden;">
        <table class="rj-table">
            <thead>
                <tr>
                    <th style="padding-left:20px;">Name</th><th>Phone</th><th>Referral Code</th>
                    <th>Portal Access</th><th>Status</th><th style="padding-right:20px;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customers as $customer)
                    <tr>
                        <td style="padding-left:20px;font-weight:600;">{{ $customer->name }}</td>
                        <td>{{ $customer->phone }}</td>
                        <td>{{ $customer->referral_code }}</td>
                        <td>{{ $customer->password ? 'Enabled' : 'Not set' }}</td>
                        <td><span class="rj-tag rj-tag-stock">{{ strtoupper(str_replace('_',' ',$customer->status)) }}</span></td>
                        <td style="padding-right:20px;text-align:right;white-space:nowrap;">
                            <button wire:click="edit({{ $customer->id }})" style="background:none;border:none;color:var(--accent);font-weight:700;font-size:12.5px;cursor:pointer;">Edit</button>
                            <button wire:click="openPasswordForm({{ $customer->id }})" style="background:none;border:none;color:#8A5F22;font-weight:600;font-size:12.5px;cursor:pointer;margin-left:10px;">
                                {{ $customer->password ? 'Reset' : 'Set' }} Password
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top:16px;">{{ $customers->links() }}</div>
</div>
</x-layouts.app>
