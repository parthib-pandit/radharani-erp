<div>
    <x-ui.page-header title="Exchange Transactions — Status Tracker" subtitle="received → melted → tested → valued → settled" />

    @php
        $stages = ['received' => 'Received', 'melted' => 'Melted', 'tested' => 'Tested', 'valued' => 'Valued', 'settled' => 'Settled'];
    @endphp

    <x-ui.card class="!p-0 overflow-hidden">
        <x-ui.table :headers="['Customer', 'Weight', 'Stage', 'Last updated']">
            @foreach ($transactions as $t)
            <tr class="h-[60px] border-b border-line-light">
                <td class="px-4 font-semibold text-ink_text-primary">{{ $t['customer'] }}</td>
                <td class="px-4 text-ink_text-primary">{{ $t['weight'] }}</td>
                <td class="px-4">
                    <div class="flex gap-1">
                        @foreach ($stages as $key => $label)
                            <span class="w-[26px] h-1.5 rounded-full {{ array_search($t['stage'], array_keys($stages)) >= array_search($key, array_keys($stages)) ? 'bg-gold' : 'bg-surface-muted' }}"></span>
                        @endforeach
                    </div>
                    <span class="text-[11px] text-ink_text-secondary">{{ $stages[$t['stage']] }}</span>
                </td>
                <td class="px-4 text-ink_text-secondary">{{ $t['updated'] }}</td>
            </tr>
            @endforeach
        </x-ui.table>
    </x-ui.card>
</div>
