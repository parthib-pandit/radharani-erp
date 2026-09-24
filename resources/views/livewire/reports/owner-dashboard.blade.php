<div>
    <x-ui.page-header title="Owner Dashboard" subtitle="Stock by location, at a glance." />

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <x-ui.stat-card icon="box" label="In Stock" :value="($inStock->qty ?? 0) . ' items'" :delta="number_format($inStock->weight ?? 0, 3) . 'g'" :delta-positive="true" />
        <x-ui.stat-card icon="truck" label="Dispatched" :value="($dispatched->qty ?? 0) . ' items'" :delta="number_format($dispatched->weight ?? 0, 3) . 'g'" :delta-positive="true" />
        <x-ui.stat-card icon="receipt" label="Sold (Lifetime)" :value="($sold->qty ?? 0) . ' items'" :delta="number_format($sold->weight ?? 0, 3) . 'g'" :delta-positive="true" />
    </div>

    <x-ui.card class="mb-6">
        <div class="text-xs font-medium text-ink_text-secondary uppercase tracking-wide mb-1.5">Today</div>
        <div class="text-lg font-bold text-ink_text-primary">₹{{ number_format($todaySales, 2) }}</div>
        <div class="text-xs text-ink_text-secondary">across {{ $todaySalesCount }} sale(s)</div>
    </x-ui.card>

    <h3 class="font-semibold text-sm text-ink_text-primary mb-2.5">Dispatched — By Location</h3>
    <x-ui.card class="!p-0 overflow-hidden">
        <x-ui.table :headers="['Location', 'Items', 'Weight']">
            @forelse ($dispatchedLocations as $location => $data)
                <tr class="h-[60px] border-b border-line-light">
                    <td class="px-4"><x-ui.badge tone="warning">{{ ucfirst($location) }}</x-ui.badge></td>
                    <td class="px-4 text-ink_text-primary">{{ $data['qty'] }}</td>
                    <td class="px-4 text-ink_text-primary">{{ number_format($data['weight'], 3) }}g</td>
                </tr>
            @empty
                <tr><td colspan="3" class="px-4 py-4 text-ink_text-secondary">Nothing dispatched right now.</td></tr>
            @endforelse
        </x-ui.table>
    </x-ui.card>
</div>
