{{-- The foot of a Configurator column: how many are shown, and Load more / View all / Show fewer. Needs $column, $shown, $total. --}}
@if ($total > $shown || ($all[$column] && $total > \App\Livewire\Stock\HierarchyConfigurator::PAGE))
    <div class="sticky bottom-0 z-[1] flex flex-wrap items-center gap-2 px-3 py-2.5 bg-white/95 backdrop-blur border-t border-line-light text-[12px]">
        <span class="text-ink_text-muted tabular">Showing {{ $shown }} of {{ $total }}</span>
        <span class="ml-auto flex gap-1.5">
            @if ($total > $shown)
                <button type="button" wire:click="loadMore('{{ $column }}')" class="press h-8 px-3 rounded-lg border border-line bg-white font-semibold text-ink_text-primary hover:bg-surface-sunken">Load more</button>
                <button type="button" wire:click="viewAll('{{ $column }}')" class="press h-8 px-3 rounded-lg font-semibold text-gold-dark hover:bg-gold-tint">View all</button>
            @else
                <button type="button" wire:click="showFewer('{{ $column }}')" class="press h-8 px-3 rounded-lg font-semibold text-gold-dark hover:bg-gold-tint">Show fewer</button>
            @endif
        </span>
    </div>
@endif
