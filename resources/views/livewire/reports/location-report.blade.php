<div>
    <x-ui.page-header title="Location Report" subtitle="Stock quantity and live-calculated value by current location." />

    <x-ui.card class="!p-0 overflow-hidden">
        <x-ui.table :headers="['Location', 'Items', 'Weight', 'Value']">
            @forelse ($rows as $row)
                <tr class="h-[60px] border-b border-line-light">
                    <td class="px-4"><x-ui.badge tone="neutral">{{ $row['location'] }}</x-ui.badge></td>
                    <td class="px-4 text-ink_text-primary">{{ $row['qty'] }}</td>
                    <td class="px-4 text-ink_text-primary">{{ number_format($row['weight'], 3) }}g</td>
                    <td class="px-4 text-ink_text-primary">₹{{ number_format($row['value'], 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-4 py-4 text-ink_text-secondary">No items in stock.</td></tr>
            @endforelse
        </x-ui.table>
    </x-ui.card>
</div>
