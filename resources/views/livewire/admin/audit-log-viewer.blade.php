<div>
    <x-ui.page-header title="Audit Log" subtitle="Every movement, sale, and purchase recorded, tamper-evident — read-only, sourced from spatie/laravel-activitylog." />

    <x-ui.card>
        <div class="flex gap-3 mb-3.5">
            <input type="text" wire:model.live.debounce.400ms="search" placeholder="Search description…" class="rj-input max-w-[280px]">
            <select wire:model.live="logNameFilter" class="rj-select max-w-[180px]">
                <option value="all">All types</option>
                <option value="movement">Movements</option>
                <option value="sale">Sales</option>
                <option value="purchase">Purchases</option>
            </select>
        </div>

        <x-ui.table :headers="['Date', 'Type', 'Description', 'By']">
            @forelse ($activities as $a)
                <tr class="h-[60px] border-b border-line-light">
                    <td class="px-4 text-ink_text-primary">{{ $a->created_at->format('d M Y H:i') }}</td>
                    <td class="px-4"><x-ui.badge tone="neutral">{{ ucfirst($a->log_name ?? 'other') }}</x-ui.badge></td>
                    <td class="px-4 text-ink_text-primary">{{ $a->description }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $a->causer->name ?? 'System' }}</td>
                </tr>
            @empty
                <tr class="h-[60px] border-b border-line-light">
                    <td colspan="4" class="px-4 text-ink_text-secondary">No audit entries yet — logging only started once Movement/Sale/Purchase were wired to the activity log.</td>
                </tr>
            @endforelse
        </x-ui.table>
        <div class="mt-3">{{ $activities->links() }}</div>
    </x-ui.card>
</div>
