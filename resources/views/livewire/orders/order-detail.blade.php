<div>
    <a href="{{ route('orders.board') }}" class="text-xs text-ink_text-secondary font-semibold">← Status board</a>

    @php
        $tones = ['placed' => 'warning', 'confirmed' => 'warning', 'ready' => 'info', 'delivered' => 'success', 'cancelled' => 'danger'];
    @endphp

    <x-ui.page-header title="{{ $order->product_description }}" subtitle="Order #{{ $order->id }} for {{ $order->customer?->name }}" />

    <div class="grid grid-cols-2 gap-5 max-w-[760px]">
        <x-ui.card>
            <div class="font-bold text-[13.5px] mb-3">Order Info</div>
            <div class="grid grid-cols-2 gap-2.5 text-[12.5px] text-ink_text-primary">
                <div><span class="text-ink_text-secondary">Customer</span><br>{{ $order->customer?->name }}</div>
                <div><span class="text-ink_text-secondary">Phone</span><br>{{ $order->customer?->phone }}</div>
                <div><span class="text-ink_text-secondary">Category / Metal</span><br>{{ $order->category ?: '—' }} {{ $order->metal ? '/ '.ucfirst($order->metal) : '' }}</div>
                <div><span class="text-ink_text-secondary">Estimated value</span><br>₹{{ number_format((float) $order->estimated_value) }}</div>
                <div><span class="text-ink_text-secondary">Advance paid</span><br>₹{{ number_format((float) $order->advance_amount) }}</div>
                <div><span class="text-ink_text-secondary">Status</span><br><x-ui.badge :tone="$tones[$order->status] ?? 'neutral'">{{ strtoupper($order->status) }}</x-ui.badge></div>
                <div><span class="text-ink_text-secondary">Placed on</span><br>{{ $order->created_at->format('d M Y') }}</div>
                <div><span class="text-ink_text-secondary">Rate</span><br>{{ $order->rate_locked ? 'Locked at ₹'.number_format((float) $order->locked_rate).' ('.$order->locked_at->format('d M Y').')' : 'Applies at delivery' }}</div>
                <div><span class="text-ink_text-secondary">Stock</span><br>{{ $order->out_of_stock ? 'Not in stock — to be made' : ($order->stockItem ? 'Linked: '.($order->stockItem->huid_code ?? $order->stockItem->internal_code) : 'In stock') }}</div>
                @if ($order->convertedSale)
                <div><span class="text-ink_text-secondary">Converted sale</span><br>Sale #{{ $order->convertedSale->id }}</div>
                @endif
            </div>

            @if (! in_array($order->status, ['delivered', 'cancelled']))
            <div class="flex flex-wrap gap-2 mt-4.5 pt-4.5 border-t border-line">
                @if ($order->status === 'placed')
                    <x-ui.button type="button" variant="primary" wire:click="confirm">Confirm Order</x-ui.button>
                @endif
                @if ($order->status === 'confirmed')
                    <x-ui.button type="button" variant="primary" wire:click="markReady">Mark Ready</x-ui.button>
                @endif
                @if ($order->status === 'ready')
                    <x-ui.button type="button" variant="primary" wire:click="deliver">Mark Delivered</x-ui.button>
                @endif
                <x-ui.button type="button" variant="danger" wire:click="cancel" onclick="return confirm('Cancel this order?')">Cancel Order</x-ui.button>
            </div>
            @endif
        </x-ui.card>

        <x-ui.card>
            <div class="font-bold text-[13.5px] mb-3">Confirmation Message</div>
            <textarea readonly rows="7" class="w-full border border-line rounded-control p-2.5 text-xs font-mono bg-surface-muted">{{ $this->confirmationMessage }}</textarea>
            <x-ui.button type="button" variant="secondary" class="w-full mt-2.5" onclick="navigator.clipboard.writeText(document.querySelector('textarea').value)">Copy Message</x-ui.button>
            <div class="mt-3.5 pt-3.5 border-t border-line">
                <div class="text-[11.5px] text-ink_text-secondary mb-1.5">Customer tracking link</div>
                <a href="{{ route('portal.login') }}" class="text-[12.5px] text-gold font-bold">Portal → My Orders</a>
            </div>
        </x-ui.card>
    </div>
</div>
