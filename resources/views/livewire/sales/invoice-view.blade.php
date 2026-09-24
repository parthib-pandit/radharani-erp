<div>
    <div class="flex justify-between items-center mb-5">
        <a href="{{ route('sales.history') }}" class="text-xs text-ink_text-secondary font-semibold">← Sales history</a>
        <x-ui.button type="button" variant="secondary" onclick="window.print()">Print</x-ui.button>
    </div>

    @unless ($sale->confirmed_by_accountant)
    <div class="bg-warning-bg text-warning rounded-control px-3.5 py-2.5 mb-5 max-w-[640px] text-xs">
        This sale is still reserved, not yet verified by admin — treat this as a preview, not a final invoice.
    </div>
    @endunless

    <x-ui.card class="max-w-[640px]">
        <div class="text-center mb-6">
            <div class="text-[22px] font-bold text-ink_text-primary">Radharani Jewellery Works</div>
            <div class="text-[11px] text-ink_text-secondary uppercase tracking-wide">Tax Invoice</div>
        </div>

        <div class="flex justify-between text-[12.5px] mb-5">
            <div>
                <div class="text-ink_text-secondary text-[11px]">Billed to</div>
                <div class="font-bold text-ink_text-primary">{{ $sale->customer->name }}</div>
                <div class="text-ink_text-primary">{{ $sale->customer->phone }}</div>
            </div>
            <div class="text-right">
                <div class="text-ink_text-secondary text-[11px]">Invoice</div>
                <div class="font-bold font-mono text-ink_text-primary">{{ $sale->invoice_number }}</div>
                <div class="text-ink_text-primary">{{ \Carbon\Carbon::parse($sale->created_at)->format('d M Y') }}</div>
            </div>
        </div>

        <x-ui.table :headers="['Item', 'Status', 'Price']">
            @foreach ($sale->items as $item)
            <tr class="h-[60px] border-b border-line-light">
                <td class="px-4 text-ink_text-primary">{{ $item->huid_code ?: $item->internal_code }} — {{ $item->category }}</td>
                <td class="px-4">
                    <x-ui.badge :tone="$item->status === 'sold' ? 'success' : 'warning'">
                        {{ $item->status === 'sold' ? 'Sold' : 'Reserved — pending verification' }}
                    </x-ui.badge>
                </td>
                <td class="px-4 text-right text-ink_text-primary">₹{{ number_format($item->pivot->price_at_sale,2) }}</td>
            </tr>
            @endforeach
        </x-ui.table>

        <div class="border-t border-line pt-3 mt-4 text-[12.5px]">
            <div class="flex justify-between py-1"><span>CGST</span><span>₹{{ number_format($sale->cgst,2) }}</span></div>
            <div class="flex justify-between py-1"><span>SGST</span><span>₹{{ number_format($sale->sgst,2) }}</span></div>
            @if ($sale->discount > 0)
            <div class="flex justify-between py-1 text-success"><span>Discount</span><span>-₹{{ number_format($sale->discount,2) }}</span></div>
            @endif
            <div class="flex justify-between text-base font-bold border-t border-line pt-2.5 mt-1.5"><span>Total</span><span>₹{{ number_format($sale->total,2) }}</span></div>
        </div>

        <div class="mt-4 text-[11.5px] text-ink_text-secondary">
            Payment: {{ collect($sale->payment_modes)->map(fn($p) => ucfirst($p['mode']).' ₹'.number_format($p['amount'],2))->join(', ') ?: '—' }}
        </div>
    </x-ui.card>
</div>
