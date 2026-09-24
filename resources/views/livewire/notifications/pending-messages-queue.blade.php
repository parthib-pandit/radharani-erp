<div>
    <x-ui.page-header title="Pending Messages Queue" subtitle="Copy each message and send it manually (WhatsApp/SMS), then mark it sent." />

    <div class="flex gap-1 bg-surface-muted rounded-control p-1 mb-4 max-w-[240px]">
        @foreach (['pending' => 'Pending', 'sent' => 'Sent', 'all' => 'All'] as $value => $label)
            <button type="button" wire:click="$set('statusFilter', '{{ $value }}')"
                class="flex-1 h-[34px] rounded-[6px] text-[12.5px] font-semibold {{ $statusFilter === $value ? 'bg-white text-ink_text-primary shadow-sm' : 'bg-transparent text-ink_text-secondary' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    <x-ui.card class="!p-0 overflow-hidden">
        <x-ui.table :headers="['Type', 'To', 'Phone', 'Message', 'Status', '']">
            @forelse ($messages as $m)
                <tr class="h-[60px] border-b border-line-light" x-data="{ copied: false }">
                    <td class="px-4"><x-ui.badge tone="neutral">{{ \Illuminate\Support\Str::headline($m->type) }}</x-ui.badge></td>
                    <td class="px-4 text-ink_text-primary">{{ $m->customer->name ?? $m->recipient_name ?? '—' }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $m->customer->phone ?? $m->recipient_phone ?? '—' }}</td>
                    <td class="px-4 text-ink_text-primary max-w-[320px] truncate" title="{{ $m->message }}">{{ $m->message }}</td>
                    <td class="px-4">
                        @if ($m->status === 'sent')
                            <x-ui.badge tone="success">Sent</x-ui.badge>
                        @else
                            <x-ui.badge tone="warning">Pending</x-ui.badge>
                        @endif
                    </td>
                    <td class="px-4 text-right whitespace-nowrap">
                        <x-ui.button type="button" variant="secondary"
                            @click="navigator.clipboard.writeText({{ json_encode($m->message) }}); copied = true; setTimeout(() => copied = false, 1500)"
                            class="mr-2">
                            <span x-show="!copied">Copy</span>
                            <span x-show="copied" x-cloak>Copied!</span>
                        </x-ui.button>
                        @if ($m->status === 'pending')
                            <x-ui.button type="button" wire:click="markSent({{ $m->id }})" variant="primary">Mark Sent</x-ui.button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-4 py-4 text-ink_text-secondary">No messages.</td></tr>
            @endforelse
        </x-ui.table>
    </x-ui.card>

    <div class="mt-4">{{ $messages->links() }}</div>
</div>
