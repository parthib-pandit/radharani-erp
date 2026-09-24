<div>
    <x-ui.page-header title="Vault ↔ Counter" subtitle="Morning send-out, evening return — scan and go." />

    <div class="grid grid-cols-2 gap-3.5 mb-6 max-w-[760px]">
        <button wire:click="setDirection('to_counter')" class="text-left cursor-pointer bg-white border rounded-card shadow-card p-5 {{ $direction === 'to_counter' ? 'border-gold' : 'border-line' }}">
            <x-ui.icon name="arrow-right" :size="20" class="text-gold" />
            <div class="font-bold text-sm text-ink_text-primary mt-1.5">Send to Counter</div>
            <div class="text-xs text-ink_text-secondary mt-0.5">Morning — moving stock out of the vault for display/sale.</div>
        </button>
        <button wire:click="setDirection('to_vault')" class="text-left cursor-pointer bg-white border rounded-card shadow-card p-5 {{ $direction === 'to_vault' ? 'border-gold' : 'border-line' }}">
            <x-ui.icon name="arrow-left" :size="20" class="text-gold" />
            <div class="font-bold text-sm text-ink_text-primary mt-1.5">Return to Vault</div>
            <div class="text-xs text-ink_text-secondary mt-0.5">Evening — closing stock, back into the vault.</div>
        </button>
    </div>

    <x-ui.card class="max-w-[480px] mb-6">
        @if ($result)
            <div class="bg-success-bg text-success rounded-control px-3.5 py-2.5 mb-4 text-sm">{{ $result }}</div>
        @endif
        @if ($error)
            <div class="bg-danger-bg text-danger rounded-control px-3.5 py-2.5 mb-4 text-sm">{{ $error }}</div>
        @endif
        <form wire:submit="submit">
            <label class="block text-[11.5px] text-ink_text-secondary mb-1">Scanning</label>
            <select wire:model="targetType" class="rj-select w-full mb-3.5">
                <option value="item">Item</option>
                <option value="packet">Packet</option>
                <option value="box">Box</option>
            </select>
            <label class="block text-[11.5px] text-ink_text-secondary mb-1">Scan or type code</label>
            <input type="text" wire:model="code" autofocus class="rj-input w-full mb-[18px]">
            <x-ui.button type="submit" variant="primary" class="w-full">
                {{ $direction === 'to_counter' ? 'Confirm — Send to Counter' : 'Confirm — Return to Vault' }}
            </x-ui.button>
        </form>
    </x-ui.card>

    <x-ui.card class="!p-0 overflow-hidden">
        <div class="px-5 py-3.5 font-bold text-[13px] border-b border-line">Today's Movements</div>
        <x-ui.table :headers="['Time', 'Direction', 'Item/Packet/Box', 'By']">
            @forelse ($today as $m)
            <tr class="h-[60px] border-b border-line-light">
                <td class="px-4 text-ink_text-primary">{{ $m->created_at->format('g:i a') }}</td>
                <td class="px-4">
                    <x-ui.badge :tone="$m->movement_type === 'vault_out' ? 'warning' : 'info'">{{ $m->movement_type === 'vault_out' ? 'TO COUNTER' : 'TO VAULT' }}</x-ui.badge>
                </td>
                <td class="px-4 text-ink_text-primary">{{ ucfirst($m->trackable_type) }} #{{ $m->trackable_id }}</td>
                <td class="px-4 text-ink_text-primary">{{ $m->user->name ?? '—' }}</td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-5 py-4 text-ink_text-secondary">No movements yet today.</td></tr>
            @endforelse
        </x-ui.table>
    </x-ui.card>
</div>
