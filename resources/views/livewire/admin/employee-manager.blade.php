<x-layouts.app title="Employees — Radharani Jewellery">
<div>
    <div class="rj-serif" style="font-size:24px;margin-bottom:4px;">Employees</div>
    <div style="font-size:13px;color:var(--muted);margin-bottom:24px;">HR records — independent of system login.</div>

    @if (session('message'))
        <div class="rj-flash">{{ session('message') }}</div>
    @endif

    <div class="rj-card" style="margin-bottom:24px;">
        <div style="font-weight:700;font-size:14px;margin-bottom:14px;">{{ $editingId ? 'Edit Employee' : 'New Employee' }}</div>
        <form wire:submit="save" style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;">
            <div>
                <label style="display:block;font-size:11.5px;color:var(--muted);margin-bottom:4px;">Name</label>
                <input type="text" wire:model="name" class="rj-input" style="width:100%;">
                @error('name') <div style="color:#B04A3C;font-size:11px;margin-top:3px;">{{ $message }}</div> @enderror
            </div>
            <div>
                <label style="display:block;font-size:11.5px;color:var(--muted);margin-bottom:4px;">Phone</label>
                <input type="text" wire:model="phone" class="rj-input" style="width:100%;">
            </div>
            <div>
                <label style="display:block;font-size:11.5px;color:var(--muted);margin-bottom:4px;">Designation</label>
                <input type="text" wire:model="designation" class="rj-input" style="width:100%;">
            </div>
            <div style="grid-column:span 2;">
                <label style="display:block;font-size:11.5px;color:var(--muted);margin-bottom:4px;">Address</label>
                <input type="text" wire:model="address" class="rj-input" style="width:100%;">
            </div>
            <div>
                <label style="display:block;font-size:11.5px;color:var(--muted);margin-bottom:4px;">Salary</label>
                <input type="number" step="0.01" wire:model="salary" class="rj-input" style="width:100%;">
            </div>
            <div>
                <label style="display:block;font-size:11.5px;color:var(--muted);margin-bottom:4px;">Joining Date</label>
                <input type="date" wire:model="joining_date" class="rj-input" style="width:100%;">
            </div>
            <div>
                <label style="display:block;font-size:11.5px;color:var(--muted);margin-bottom:4px;">Status</label>
                <select wire:model="status" class="rj-select" style="width:100%;">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
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

    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search employees..."
        class="rj-input" style="margin-bottom:14px;width:280px;">

    <div class="rj-card" style="padding:0;overflow:hidden;">
        <table class="rj-table">
            <thead>
                <tr>
                    <th style="padding-left:20px;">Name</th><th>Designation</th><th>Phone</th>
                    <th>Has Login</th><th>Status</th><th style="padding-right:20px;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $employee)
                    <tr>
                        <td style="padding-left:20px;font-weight:600;">{{ $employee->name }}</td>
                        <td>{{ $employee->designation }}</td>
                        <td>{{ $employee->phone }}</td>
                        <td>{{ $employee->user_count > 0 ? 'Yes' : 'No' }}</td>
                        <td>
                            <span class="rj-tag {{ $employee->status === 'active' ? 'rj-tag-stock' : 'rj-tag-sold' }}">
                                {{ strtoupper($employee->status) }}
                            </span>
                        </td>
                        <td style="padding-right:20px;text-align:right;white-space:nowrap;">
                            <button wire:click="edit({{ $employee->id }})" style="background:none;border:none;color:var(--accent);font-weight:700;font-size:12.5px;cursor:pointer;">Edit</button>
                            @if ($employee->status === 'active')
                                <button wire:click="deactivate({{ $employee->id }})" onclick="return confirm('Mark inactive?')" style="background:none;border:none;color:#B04A3C;font-weight:600;font-size:12.5px;cursor:pointer;margin-left:10px;">Deactivate</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top:16px;">{{ $employees->links() }}</div>
</div>
</x-layouts.app>
