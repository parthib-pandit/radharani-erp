<div>
    <x-ui.page-header title="Custom Order Status Board" subtitle="placed → confirmed → ready → delivered · cancelled off to the side" />

    @php
        $columns = ['placed' => 'Placed', 'confirmed' => 'Confirmed', 'ready' => 'Ready', 'delivered' => 'Delivered', 'cancelled' => 'Cancelled'];
    @endphp

    <div class="grid grid-cols-5 gap-3">
        @foreach ($columns as $key => $label)
        <div>
            <div class="text-[11.5px] font-bold uppercase tracking-wide text-ink_text-secondary mb-2.5 pl-0.5">
                {{ $label }} ({{ $orders->get($key, collect())->count() }})
            </div>
            <div class="flex flex-col gap-2.5">
                @foreach ($orders->get($key, collect()) as $o)
                <a href="{{ route('orders.show', $o->id) }}" class="block bg-white border border-line-light rounded-card shadow-card p-3.5 {{ $key === 'cancelled' ? 'opacity-60' : '' }}">
                    <div class="font-bold text-[12.5px] text-ink_text-primary">{{ $o->customer?->name }}</div>
                    <div class="text-[11.5px] text-ink_text-secondary mt-1">{{ $o->product_description }}</div>
                    <div class="text-xs font-semibold mt-2 text-gold-dark">₹{{ number_format((float) $o->estimated_value) }}</div>
                </a>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>
