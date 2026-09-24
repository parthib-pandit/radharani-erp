<div>
    <x-ui.page-header title="Customers" subtitle="Directory, plus portal password assignment — customers cannot self-register." />

    @if (session('message'))
        <div class="bg-success-bg text-success rounded-control px-3.5 py-2.5 mb-5 text-sm">{{ session('message') }}</div>
    @endif

    <x-ui.card class="mb-6">
        <div class="font-semibold text-sm text-ink_text-primary mb-3.5">{{ $editingId ? 'Edit Customer' : 'New Customer' }}</div>
        <form wire:submit="save" class="grid grid-cols-3 gap-3.5">
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Name</label>
                <input type="text" wire:model="name" class="rj-input w-full">
                @error('name') <div class="text-danger text-[11px] mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Phone</label>
                <input type="text" wire:model="phone" class="rj-input w-full">
                @error('phone') <div class="text-danger text-[11px] mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Email</label>
                <input type="email" wire:model="email" class="rj-input w-full">
            </div>
            <div class="col-span-2">
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Address</label>
                <input type="text" wire:model="address" class="rj-input w-full">
            </div>
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">GSTIN (optional)</label>
                <input type="text" wire:model="gstin" class="rj-input w-full">
            </div>
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Status</label>
                <select wire:model="status" class="rj-select w-full">
                    <option value="past_customer">Past Customer</option>
                    <option value="order_given">Order Given</option>
                    <option value="order_pending">Order Pending</option>
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

    @if ($showPasswordFor)
        <x-ui.card class="mb-6 max-w-[400px]">
            <div class="font-semibold text-sm text-ink_text-primary mb-2.5">Set Portal Password</div>
            <div class="text-xs text-ink_text-secondary mb-3">Share this password with the customer directly — there is no reset-by-email flow yet.</div>
            <input type="text" wire:model="newPassword" class="rj-input w-full mb-2.5" placeholder="New password (min 8 characters)">
            @error('newPassword') <div class="text-danger text-[11px] mb-2">{{ $message }}</div> @enderror
            <div class="flex gap-2.5">
                <x-ui.button wire:click="setPassword" variant="primary">Set Password</x-ui.button>
                <x-ui.button wire:click="$set('showPasswordFor', false)" variant="secondary">Cancel</x-ui.button>
            </div>
        </x-ui.card>
    @endif

    <div class="flex justify-between items-center mb-3.5">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search customers..."
            class="rj-input w-[280px]">
        <a href="{{ route('admin.customers.import') }}" wire:navigate><x-ui.button variant="secondary">Bulk Import</x-ui.button></a>
    </div>

    <x-ui.card class="!p-0 overflow-hidden">
        <x-ui.table :headers="['Name', 'Phone', 'Referral Code', 'Portal Access', 'Status', '']">
            @foreach ($customers as $customer)
                <tr class="h-[60px] border-b border-line-light">
                    <td class="px-4 font-semibold text-ink_text-primary">{{ $customer->name }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $customer->phone }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $customer->referral_code }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $customer->password ? 'Enabled' : 'Not set' }}</td>
                    <td class="px-4"><x-ui.badge tone="success">{{ strtoupper(str_replace('_',' ',$customer->status)) }}</x-ui.badge></td>
                    <td class="px-4 text-right whitespace-nowrap">
                        <a href="{{ route('admin.customers.detail', $customer) }}" wire:navigate class="text-gold font-semibold text-xs mr-3.5">Detail</a>
                        <button wire:click="edit({{ $customer->id }})" class="bg-transparent border-0 text-gold font-semibold text-xs cursor-pointer mr-3.5">Edit</button>
                        <button wire:click="openPasswordForm({{ $customer->id }})" class="bg-transparent border-0 text-warning font-semibold text-xs cursor-pointer">
                            {{ $customer->password ? 'Reset' : 'Set' }} Password
                        </button>
                    </td>
                </tr>
            @endforeach
        </x-ui.table>
    </x-ui.card>

    <div class="mt-4">{{ $customers->links() }}</div>
</div>
