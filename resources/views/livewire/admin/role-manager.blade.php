<x-layouts.app title="Roles & Permissions — Radharani Jewellery">
<div>
    <div class="rj-serif" style="font-size:24px;margin-bottom:4px;">Roles &amp; Permissions</div>
    <div style="font-size:13px;color:var(--muted);margin-bottom:24px;">Define what each role is allowed to do. New roles need no code changes.</div>

    @if (session('message'))
        <div class="rj-flash">{{ session('message') }}</div>
    @endif
    @if (session('error'))
        <div class="rj-flash" style="background:#F6E3DF;color:#8A3B2B;">{{ session('error') }}</div>
    @endif

    @if (!$showNewRoleForm)
        <button wire:click="newRole" class="rj-btn-primary" style="margin-bottom:20px;">+ New Role</button>
    @endif

    @if ($showNewRoleForm)
        <div class="rj-card" style="margin-bottom:24px;">
            <div style="font-weight:700;font-size:14px;margin-bottom:14px;">{{ $editingId ? 'Edit Role' : 'New Role' }}</div>
            <form wire:submit="save">
                <div style="margin-bottom:14px;max-width:260px;">
                    <label style="display:block;font-size:11.5px;color:var(--muted);margin-bottom:4px;">Role Name</label>
                    <input type="text" wire:model="name" class="rj-input" style="width:100%;" placeholder="e.g. counter_staff">
                    @error('name') <div style="color:#B04A3C;font-size:11px;margin-top:3px;">{{ $message }}</div> @enderror
                </div>

                <label style="display:block;font-size:11.5px;color:var(--muted);margin-bottom:8px;">Permissions</label>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-bottom:18px;">
                    @foreach ($allPermissions as $permission)
                        <label style="display:flex;align-items:center;gap:6px;font-size:12.5px;">
                            <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission }}">
                            {{ $permission }}
                        </label>
                    @endforeach
                </div>

                <div style="display:flex;gap:10px;">
                    <button type="submit" class="rj-btn-primary">Save Role</button>
                    <button type="button" wire:click="cancel" class="rj-btn-secondary">Cancel</button>
                </div>
            </form>
        </div>
    @endif

    <div class="rj-card" style="padding:0;overflow:hidden;">
        <table class="rj-table">
            <thead>
                <tr><th style="padding-left:20px;">Role</th><th># Permissions</th><th># Users</th><th style="padding-right:20px;"></th></tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr>
                        <td style="padding-left:20px;font-weight:600;">{{ ucwords(str_replace('_',' ',$role->name)) }}</td>
                        <td>{{ $role->permissions_count }}</td>
                        <td>{{ $role->users_count }}</td>
                        <td style="padding-right:20px;text-align:right;white-space:nowrap;">
                            <button wire:click="edit({{ $role->id }})" style="background:none;border:none;color:var(--accent);font-weight:700;font-size:12.5px;cursor:pointer;">Edit</button>
                            <button wire:click="delete({{ $role->id }})" onclick="return confirm('Delete this role?')" style="background:none;border:none;color:#B04A3C;font-weight:600;font-size:12.5px;cursor:pointer;margin-left:10px;">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</x-layouts.app>
