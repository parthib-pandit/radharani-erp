<x-layouts.app title="Users — Radharani Jewellery">
<div>
    <div class="rj-serif" style="font-size:24px;margin-bottom:4px;">Users &amp; Logins</div>
    <div style="font-size:13px;color:var(--muted);margin-bottom:24px;">System access — every action is attributed to one of these accounts.</div>

    @if (session('message'))
        <div class="rj-flash">{{ session('message') }}</div>
    @endif

    <div class="rj-card" style="margin-bottom:24px;">
        <div style="font-weight:700;font-size:14px;margin-bottom:14px;">{{ $editingId ? 'Edit User' : 'New User' }}</div>
        <form wire:submit="save" style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;">
            <div>
                <label style="display:block;font-size:11.5px;color:var(--muted);margin-bottom:4px;">Name</label>
                <input type="text" wire:model="name" class="rj-input" style="width:100%;">
                @error('name') <div style="color:#B04A3C;font-size:11px;margin-top:3px;">{{ $message }}</div> @enderror
            </div>
            <div>
                <label style="display:block;font-size:11.5px;color:var(--muted);margin-bottom:4px;">Email</label>
                <input type="email" wire:model="email" class="rj-input" style="width:100%;">
                @error('email') <div style="color:#B04A3C;font-size:11px;margin-top:3px;">{{ $message }}</div> @enderror
            </div>
            <div>
                <label style="display:block;font-size:11.5px;color:var(--muted);margin-bottom:4px;">
                    Password {{ $editingId ? '(leave blank to keep)' : '' }}
                </label>
                <input type="password" wire:model="password" class="rj-input" style="width:100%;">
                @error('password') <div style="color:#B04A3C;font-size:11px;margin-top:3px;">{{ $message }}</div> @enderror
            </div>
            <div>
                <label style="display:block;font-size:11.5px;color:var(--muted);margin-bottom:4px;">Link to Employee</label>
                <select wire:model="employee_id" class="rj-select" style="width:100%;">
                    <option value="">— none —</option>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="grid-column:span 2;">
                <label style="display:block;font-size:11.5px;color:var(--muted);margin-bottom:4px;">Roles</label>
                <div style="display:flex;flex-wrap:wrap;gap:10px;">
                    @foreach ($roles as $roleName)
                        <label style="display:flex;align-items:center;gap:5px;font-size:12.5px;background:#F9F7F2;border:1px solid var(--border);border-radius:20px;padding:5px 12px;">
                            <input type="checkbox" wire:model="selectedRoles" value="{{ $roleName }}">
                            {{ ucwords(str_replace('_',' ',$roleName)) }}
                        </label>
                    @endforeach
                </div>
                @error('selectedRoles') <div style="color:#B04A3C;font-size:11px;margin-top:3px;">{{ $message }}</div> @enderror
            </div>
            <div style="grid-column:1/-1;display:flex;gap:10px;align-items:center;">
                <button type="submit" class="rj-btn-primary">Save</button>
                @if ($editingId)
                    <button type="button" wire:click="cancel" class="rj-btn-secondary">Cancel</button>
                @endif
            </div>
        </form>
    </div>

    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search users..."
        class="rj-input" style="margin-bottom:14px;width:280px;">

    <div class="rj-card" style="padding:0;overflow:hidden;">
        <table class="rj-table">
            <thead>
                <tr>
                    <th style="padding-left:20px;">Name</th><th>Email</th><th>Roles</th>
                    <th>Employee</th><th>Status</th><th style="padding-right:20px;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td style="padding-left:20px;font-weight:600;">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->roles->pluck('name')->map(fn($r) => ucwords(str_replace('_',' ',$r)))->join(', ') }}</td>
                        <td>{{ $user->employee?->name ?? '—' }}</td>
                        <td>
                            <span class="rj-tag {{ $user->is_active ? 'rj-tag-stock' : 'rj-tag-sold' }}">
                                {{ $user->is_active ? 'ACTIVE' : 'DISABLED' }}
                            </span>
                        </td>
                        <td style="padding-right:20px;text-align:right;white-space:nowrap;">
                            <button wire:click="edit({{ $user->id }})" style="background:none;border:none;color:var(--accent);font-weight:700;font-size:12.5px;cursor:pointer;">Edit</button>
                            <button wire:click="toggleActive({{ $user->id }})" onclick="return confirm('{{ $user->is_active ? 'Disable' : 'Enable' }} this login?')" style="background:none;border:none;color:#B04A3C;font-weight:600;font-size:12.5px;cursor:pointer;margin-left:10px;">
                                {{ $user->is_active ? 'Disable' : 'Enable' }}
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top:16px;">{{ $users->links() }}</div>
</div>
</x-layouts.app>
