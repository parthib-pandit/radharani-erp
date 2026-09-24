<div>
    <x-ui.page-header title="Users & Logins" subtitle="System access — every action is attributed to one of these accounts." />

    @if (session('message'))
        <div class="bg-success-bg text-success rounded-control px-3.5 py-2.5 mb-5 text-sm">{{ session('message') }}</div>
    @endif

    <x-ui.card class="mb-6">
        <div class="font-semibold text-sm text-ink_text-primary mb-4">{{ $editingId ? 'Edit User' : 'New User' }}</div>
        <form wire:submit="save" class="grid grid-cols-3 gap-3.5">
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Name</label>
                <input type="text" wire:model="name" class="rj-input w-full">
                @error('name') <div class="text-danger text-[11px] mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Email</label>
                <input type="email" wire:model="email" class="rj-input w-full">
                @error('email') <div class="text-danger text-[11px] mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">
                    Password {{ $editingId ? '(leave blank to keep)' : '' }}
                </label>
                <input type="password" wire:model="password" class="rj-input w-full">
                @error('password') <div class="text-danger text-[11px] mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Link to Employee</label>
                <select wire:model="employee_id" class="rj-select w-full">
                    <option value="">— none —</option>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-2">
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Roles</label>
                <div class="flex flex-wrap gap-2.5">
                    @foreach ($roles as $roleName)
                        <label class="flex items-center gap-1.5 text-[12.5px] bg-surface-muted border border-line rounded-full px-3 py-1.5">
                            <input type="checkbox" wire:model="selectedRoles" value="{{ $roleName }}" class="accent-gold">
                            {{ ucwords(str_replace('_',' ',$roleName)) }}
                        </label>
                    @endforeach
                </div>
                @error('selectedRoles') <div class="text-danger text-[11px] mt-1">{{ $message }}</div> @enderror
            </div>
            <div class="col-span-full flex gap-2.5 items-center">
                <x-ui.button type="submit" variant="primary">Save</x-ui.button>
                @if ($editingId)
                    <x-ui.button type="button" wire:click="cancel" variant="secondary">Cancel</x-ui.button>
                @endif
            </div>
        </form>
    </x-ui.card>

    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search users..."
        class="rj-input mb-3.5 w-[280px]">

    <x-ui.card class="!p-0 overflow-hidden">
        <x-ui.table :headers="['Name', 'Email', 'Roles', 'Employee', 'Status', '']">
            @foreach ($users as $user)
                <tr class="h-[60px] border-b border-line-light">
                    <td class="px-4 font-semibold text-ink_text-primary">{{ $user->name }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $user->email }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $user->roles->pluck('name')->map(fn($r) => ucwords(str_replace('_',' ',$r)))->join(', ') }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $user->employee?->name ?? '—' }}</td>
                    <td class="px-4">
                        <x-ui.badge :tone="$user->is_active ? 'success' : 'neutral'">
                            {{ $user->is_active ? 'ACTIVE' : 'DISABLED' }}
                        </x-ui.badge>
                    </td>
                    <td class="px-4 text-right whitespace-nowrap">
                        <button wire:click="edit({{ $user->id }})" class="bg-transparent border-0 text-gold font-semibold text-xs cursor-pointer">Edit</button>
                        <button wire:click="toggleActive({{ $user->id }})" onclick="return confirm('{{ $user->is_active ? 'Disable' : 'Enable' }} this login?')" class="bg-transparent border-0 text-danger font-semibold text-xs cursor-pointer ml-2.5">
                            {{ $user->is_active ? 'Disable' : 'Enable' }}
                        </button>
                    </td>
                </tr>
            @endforeach
        </x-ui.table>
    </x-ui.card>

    <div class="mt-4">{{ $users->links() }}</div>
</div>
