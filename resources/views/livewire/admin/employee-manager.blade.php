<div>
    <x-ui.page-header title="Employees" subtitle="HR records — independent of system login." />

    @if (session('message'))
        <div class="bg-success-bg text-success rounded-control px-3.5 py-2.5 mb-5 text-sm">{{ session('message') }}</div>
    @endif

    <x-ui.card class="mb-6">
        <div class="font-semibold text-sm text-ink_text-primary mb-4">{{ $editingId ? 'Edit Employee' : 'New Employee' }}</div>
        <form wire:submit="save" class="grid grid-cols-3 gap-3.5">
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Name</label>
                <input type="text" wire:model="name" class="rj-input w-full">
                @error('name') <div class="text-danger text-[11px] mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Phone</label>
                <input type="text" wire:model="phone" class="rj-input w-full">
            </div>
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Designation</label>
                <input type="text" wire:model="designation" class="rj-input w-full">
            </div>
            <div class="col-span-2">
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Address</label>
                <input type="text" wire:model="address" class="rj-input w-full">
            </div>
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Salary</label>
                <input type="number" step="0.01" wire:model="salary" class="rj-input w-full">
            </div>
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Joining Date</label>
                <input type="date" wire:model="joining_date" class="rj-input w-full">
            </div>
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Status</label>
                <select wire:model="status" class="rj-select w-full">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="col-span-full flex gap-2.5">
                <x-ui.button type="submit" variant="primary">Save</x-ui.button>
                @if ($editingId)
                    <x-ui.button type="button" wire:click="cancel" variant="secondary">Cancel</x-ui.button>
                @endif
            </div>
        </form>
    </x-ui.card>

    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search employees..."
        class="rj-input mb-3.5 w-[280px]">

    <x-ui.card class="!p-0 overflow-hidden">
        <x-ui.table :headers="['Name', 'Designation', 'Phone', 'Has Login', 'Status', '']">
            @foreach ($employees as $employee)
                <tr class="h-[60px] border-b border-line-light">
                    <td class="px-4 font-semibold text-ink_text-primary">{{ $employee->name }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $employee->designation }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $employee->phone }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $employee->user_count > 0 ? 'Yes' : 'No' }}</td>
                    <td class="px-4">
                        <x-ui.badge :tone="$employee->status === 'active' ? 'success' : 'neutral'">
                            {{ strtoupper($employee->status) }}
                        </x-ui.badge>
                    </td>
                    <td class="px-4 text-right whitespace-nowrap">
                        <button wire:click="edit({{ $employee->id }})" class="bg-transparent border-0 text-gold font-semibold text-xs cursor-pointer">Edit</button>
                        @if ($employee->status === 'active')
                            <button wire:click="deactivate({{ $employee->id }})" onclick="return confirm('Mark inactive?')" class="bg-transparent border-0 text-danger font-semibold text-xs cursor-pointer ml-2.5">Deactivate</button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </x-ui.table>
    </x-ui.card>

    <div class="mt-4">{{ $employees->links() }}</div>
</div>
