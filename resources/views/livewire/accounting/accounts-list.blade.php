<div>
    <x-ui.page-header title="Accounts" subtitle="Basic chart of accounts — asset, liability, income, expense." />

    @if (session('message'))
        <div class="bg-success-bg text-success rounded-control px-3.5 py-2.5 mb-5 text-sm">{{ session('message') }}</div>
    @endif

    <x-ui.card class="mb-6 max-w-[480px]">
        <div class="font-semibold text-sm text-ink_text-primary mb-4">{{ $editingId ? 'Edit Account' : 'Add Account' }}</div>
        <form wire:submit="save">
            <div class="mb-3">
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Name</label>
                <input type="text" wire:model="name" class="rj-input w-full">
                @error('name') <div class="text-danger text-[11px] mt-1">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Type</label>
                <select wire:model="type" class="rj-select w-full">
                    <option value="asset">Asset</option>
                    <option value="liability">Liability</option>
                    <option value="income">Income</option>
                    <option value="expense">Expense</option>
                </select>
            </div>
            <div class="flex gap-2">
                <x-ui.button type="submit" variant="primary">{{ $editingId ? 'Update' : 'Add' }} Account</x-ui.button>
                @if ($editingId)
                    <x-ui.button type="button" wire:click="cancel" variant="secondary">Cancel</x-ui.button>
                @endif
            </div>
        </form>
    </x-ui.card>

    <x-ui.card class="!p-0 overflow-hidden max-w-[640px]">
        <x-ui.table :headers="['Name', 'Type', 'Transactions', '']">
            @forelse ($accounts as $a)
                <tr class="h-[60px] border-b border-line-light">
                    <td class="px-4 font-semibold text-ink_text-primary">{{ $a->name }}</td>
                    <td class="px-4"><x-ui.badge tone="neutral">{{ ucfirst($a->type) }}</x-ui.badge></td>
                    <td class="px-4 text-ink_text-primary">{{ $a->transactions_count }}</td>
                    <td class="px-4 text-right whitespace-nowrap">
                        <button wire:click="edit({{ $a->id }})" class="bg-transparent border-0 text-gold font-semibold text-xs cursor-pointer">Edit</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-4 py-4 text-ink_text-secondary">No accounts yet.</td></tr>
            @endforelse
        </x-ui.table>
    </x-ui.card>
</div>
