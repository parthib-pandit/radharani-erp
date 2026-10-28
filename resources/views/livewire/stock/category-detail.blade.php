<div>
    <x-ui.page-header :title="$category->name" :subtitle="ucfirst($category->metal) . ' · ' . number_format($stats->pieces) . ' ' . \Illuminate\Support\Str::plural('piece', $stats->pieces) . ' · ' . number_format($stats->weight, 3) . ' g (sold pieces left out)'"
        :crumbs="[['label' => 'Stock', 'href' => route('stock.items')], ['label' => 'Categories', 'href' => route('stock.categories')], ['label' => $category->name]]" />

    <x-ui.datatable :paginator="$rows">
        <x-slot:toolbar>
            <x-ui.search-input scan wire:model.live.debounce.300ms="search" placeholder="Search by code, HUID, box or packet" class="w-full sm:w-[320px]" />
        </x-slot:toolbar>

        <x-slot:head>
            <x-ui.th field="code" :sort-field="$this->currentSortField()" :sort-direction="$this->currentSortDirection()">Piece</x-ui.th>
            <x-ui.th field="weight" :sort-field="$this->currentSortField()" :sort-direction="$this->currentSortDirection()" align="right">Weight</x-ui.th>
            <x-ui.th>Box</x-ui.th>
            <x-ui.th>Packet</x-ui.th>
            <x-ui.th>Where it is</x-ui.th>
            <x-ui.th field="status" :sort-field="$this->currentSortField()" :sort-direction="$this->currentSortDirection()">Status</x-ui.th>
        </x-slot:head>

        @forelse ($rows as $item)
            <tr wire:key="cat-item-{{ $item->id }}">
                <td><a href="{{ route('stock.items.show', $item) }}" class="rj-code font-semibold text-gold-dark hover:underline">{{ $item->label }}</a></td>
                <td class="text-right tabular">{{ number_format($item->weight, 3) }} g</td>
                <td>@if ($item->packet?->box)<a href="{{ route('stock.boxes.show', $item->packet->box) }}" class="rj-code hover:underline">{{ $item->packet->box->code }}</a>@else<span class="text-ink_text-muted">-</span>@endif</td>
                <td>@if ($item->packet)<a href="{{ route('stock.packets.show', $item->packet) }}" class="rj-code hover:underline">{{ $item->packet->code }}</a>@else<span class="text-ink_text-muted">-</span>@endif</td>
                <td class="text-ink_text-secondary">{{ $places[$item->id] }}</td>
                <td><x-ui.status :status="$item->status" /></td>
            </tr>
        @empty
            <tr><td colspan="6"><x-ui.empty-state icon="gem" title="No pieces" :message="$search !== '' ? 'Nothing matches that search.' : 'No piece is in this subcategory yet.'" compact /></td></tr>
        @endforelse
    </x-ui.datatable>
</div>
