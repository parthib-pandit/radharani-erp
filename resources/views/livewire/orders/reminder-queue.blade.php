<div>
    <x-ui.page-header title="Order Ready-for-Collection Reminders" subtitle="Copy the message, send it manually, mark it sent — admin verifies once the customer actually collects." />

    @if (session('message'))<div class="bg-success-bg text-success rounded-control px-3.5 py-2.5 mb-5 text-sm">{{ session('message') }}</div>@endif

    <x-ui.card class="!p-0 overflow-hidden">
        <x-ui.table :headers="['Customer', 'Item', 'Ready since', 'Message', 'Status']">
            @forelse ($ready as $o)
            <tr class="h-[60px] border-b border-line-light">
                <td class="px-4 font-semibold text-ink_text-primary">{{ $o->customer?->name }}<div class="text-[11px] text-ink_text-secondary font-normal">{{ $o->customer?->phone }}</div></td>
                <td class="px-4 text-ink_text-primary">{{ $o->product_description }}</td>
                <td class="px-4 text-ink_text-primary">{{ $o->updated_at->diffForHumans() }}</td>
                <td class="px-4">
                    <button onclick="navigator.clipboard.writeText('Hi {{ $o->customer?->name }}, your order ({{ $o->product_description }}) is ready for collection at Radharani Jewellery Works.')"
                        class="bg-transparent border border-line rounded-md px-2.5 py-1.5 text-[11.5px] cursor-pointer">Copy Text</button>
                </td>
                <td class="px-4">
                    @if ($o->notificationSent)
                        <x-ui.badge tone="warning" class="mr-2">SENT</x-ui.badge>
                        <button wire:click="verify({{ $o->id }})" onclick="return confirm('Confirm customer has collected this order?')" class="bg-transparent border-0 text-gold font-bold text-[11.5px] cursor-pointer">Admin: Verify Collected</button>
                    @else
                        <button wire:click="markSent({{ $o->id }})" class="bg-transparent border-0 text-gold font-bold text-[11.5px] cursor-pointer">Mark Sent</button>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td class="px-4 py-6 text-ink_text-secondary text-[12.5px]" colspan="5">No orders currently waiting on collection.</td></tr>
            @endforelse
        </x-ui.table>
    </x-ui.card>
</div>
