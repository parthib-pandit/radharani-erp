<div class="flex-1 max-w-[400px] relative" x-data="{ open: false }" @click.outside="open = false">
    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-ink_text-muted pointer-events-none">
        <x-ui.icon name="search" :size="15" />
    </span>
    <input type="text" wire:model.live.debounce.300ms="query" @focus="open = true" placeholder="Search jewellery, order, customer..."
           class="w-full h-10 rounded-control border border-line bg-surface-bg pl-9 pr-3 text-[13px] text-ink_text-primary placeholder:text-ink_text-muted focus:outline-none focus:border-gold focus:ring-4 focus:ring-gold/10">

    @if ($query && strlen($query) >= 2)
        <div x-show="open" x-cloak class="absolute left-0 right-0 mt-2 bg-white border border-line rounded-card shadow-card max-h-[420px] overflow-y-auto z-50">
            @php($results = $this->results)
            @if (($results['items'] ?? collect())->isEmpty() && ($results['customers'] ?? collect())->isEmpty() && ($results['orders'] ?? collect())->isEmpty())
                <div class="px-4 py-3 text-sm text-ink_text-secondary">No matches for "{{ $query }}".</div>
            @else
                @if (($results['items'] ?? collect())->isNotEmpty())
                    <div class="px-4 pt-3 pb-1 text-[11px] font-semibold text-ink_text-muted uppercase tracking-wide">Inventory</div>
                    @foreach ($results['items'] as $item)
                        <a href="{{ route('stock.items.show', $item) }}" class="flex items-center gap-3 px-4 py-2 hover:bg-surface-muted">
                            <x-ui.icon name="gem" :size="15" class="text-gold shrink-0" />
                            <span class="text-sm text-ink_text-primary truncate">{{ $item->huid_code ?: $item->internal_code }} — {{ $item->category }}</span>
                        </a>
                    @endforeach
                @endif
                @if (($results['customers'] ?? collect())->isNotEmpty())
                    <div class="px-4 pt-3 pb-1 text-[11px] font-semibold text-ink_text-muted uppercase tracking-wide">Customers</div>
                    @foreach ($results['customers'] as $customer)
                        <a href="{{ route('admin.customers.detail', $customer) }}" class="flex items-center gap-3 px-4 py-2 hover:bg-surface-muted">
                            <x-ui.icon name="user" :size="15" class="text-gold shrink-0" />
                            <span class="text-sm text-ink_text-primary truncate">{{ $customer->name }} — {{ $customer->phone }}</span>
                        </a>
                    @endforeach
                @endif
                @if (($results['orders'] ?? collect())->isNotEmpty())
                    <div class="px-4 pt-3 pb-1 text-[11px] font-semibold text-ink_text-muted uppercase tracking-wide">Orders</div>
                    @foreach ($results['orders'] as $order)
                        <a href="{{ route('orders.show', $order) }}" class="flex items-center gap-3 px-4 py-2 hover:bg-surface-muted">
                            <x-ui.icon name="file-text" :size="15" class="text-gold shrink-0" />
                            <span class="text-sm text-ink_text-primary truncate">{{ $order->product_description }}</span>
                        </a>
                    @endforeach
                @endif
            @endif
        </div>
    @endif
</div>
