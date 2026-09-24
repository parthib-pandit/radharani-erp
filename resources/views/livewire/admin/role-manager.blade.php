<div>
    <x-ui.page-header title="Roles & Permissions" subtitle="Define what each role is allowed to do. New roles need no code changes." />

    @if (session('message'))
        <div class="bg-success-bg text-success rounded-control px-3.5 py-2.5 mb-5 text-sm">{{ session('message') }}</div>
    @endif
    @if (session('error'))
        <div class="bg-danger-bg text-danger rounded-control px-3.5 py-2.5 mb-5 text-sm">{{ session('error') }}</div>
    @endif

    @if (!$showNewRoleForm)
        <x-ui.button wire:click="newRole" variant="primary" class="mb-5">+ New Role</x-ui.button>
    @endif

    @if ($showNewRoleForm)
        <x-ui.card class="mb-6">
            <div class="font-semibold text-sm text-ink_text-primary mb-4">{{ $editingId ? 'Edit Role' : 'New Role' }}</div>
            <form wire:submit="save">
                <div class="mb-3.5 max-w-[260px]">
                    <label class="block text-[11.5px] text-ink_text-secondary mb-1">Role Name</label>
                    <input type="text" wire:model="name" class="rj-input w-full" placeholder="e.g. counter_staff">
                    @error('name') <div class="text-danger text-[11px] mt-1">{{ $message }}</div> @enderror
                </div>

                <label class="block text-[11.5px] text-ink_text-secondary mb-2">Permissions</label>
                <div class="grid grid-cols-3 gap-2 mb-[18px]">
                    @foreach ($allPermissions as $permission)
                        <label class="flex items-center gap-1.5 text-[12.5px] text-ink_text-primary">
                            <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission }}" class="accent-gold">
                            {{ $permission }}
                        </label>
                    @endforeach
                </div>

                <div class="flex gap-2.5">
                    <x-ui.button type="submit" variant="primary">Save Role</x-ui.button>
                    <x-ui.button type="button" wire:click="cancel" variant="secondary">Cancel</x-ui.button>
                </div>
            </form>
        </x-ui.card>
    @endif

    <x-ui.card class="!p-0 overflow-hidden">
        <x-ui.table :headers="['Role', '# Permissions', '# Users', '']">
            @foreach ($roles as $role)
                <tr class="h-[60px] border-b border-line-light">
                    <td class="px-4 font-semibold text-ink_text-primary">{{ ucwords(str_replace('_',' ',$role->name)) }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $role->permissions_count }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $role->users_count }}</td>
                    <td class="px-4 text-right whitespace-nowrap">
                        <button wire:click="edit({{ $role->id }})" class="bg-transparent border-0 text-gold font-semibold text-xs cursor-pointer">Edit</button>
                        <button wire:click="delete({{ $role->id }})" onclick="return confirm('Delete this role?')" class="bg-transparent border-0 text-danger font-semibold text-xs cursor-pointer ml-2.5">Delete</button>
                    </td>
                </tr>
            @endforeach
        </x-ui.table>
    </x-ui.card>
</div>
