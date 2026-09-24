<div>
    <x-ui.page-header title="Rate History Log" subtitle="Every past rate entry, most recent first." />

    <x-ui.card class="!p-0 overflow-hidden max-w-[640px]">
        <x-ui.table :headers="['Date & time', 'Metal', 'Rate (₹/g)', 'Source', 'By']">
            @forelse ($rates as $r)
            <tr class="h-[60px] border-b border-line-light">
                <td class="px-4 text-ink_text-primary">{{ $r->created_at->format('d M Y, g:i a') }}</td>
                <td class="px-4"><x-ui.badge :tone="$r->metal === 'gold' ? 'warning' : 'neutral'">{{ strtoupper($r->metal) }}</x-ui.badge></td>
                <td class="px-4 text-ink_text-primary">₹{{ number_format($r->rate, 2) }}</td>
                <td class="px-4 text-ink_text-primary capitalize">{{ $r->source }}</td>
                <td class="px-4 text-ink_text-primary">{{ $r->updater->name ?? '—' }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-5 py-4 text-ink_text-secondary">No rate entries yet.</td></tr>
            @endforelse
        </x-ui.table>
    </x-ui.card>
    <div class="mt-4">{{ $rates->links() }}</div>
</div>
