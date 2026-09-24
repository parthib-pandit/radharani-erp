<div>
    <div class="flex items-baseline justify-between mb-1">
        <div class="text-2xl font-bold text-ink_text-primary">{{ $customer->name }}</div>
        <a href="{{ route('admin.customers') }}" wire:navigate class="text-[12.5px] text-gold">&larr; Back to Customers</a>
    </div>
    <div class="text-sm text-ink_text-secondary mb-5">
        {{ $customer->phone }} · {{ $customer->email ?: 'no email' }} · Referral code {{ $customer->referral_code ?? '—' }}
    </div>

    <div class="grid grid-cols-3 gap-3.5 mb-6">
        <x-ui.card>
            <div class="text-[11px] text-ink_text-muted uppercase">Exchange Balance</div>
            <div class="text-xl font-semibold text-ink_text-primary mt-1">₹{{ number_format($customer->balance, 2) }}</div>
        </x-ui.card>
        <x-ui.card>
            <div class="text-[11px] text-ink_text-muted uppercase">Loyalty Points</div>
            <div class="text-xl font-semibold text-ink_text-primary mt-1">{{ $customer->loyalty_points }}</div>
        </x-ui.card>
        <x-ui.card>
            <div class="text-[11px] text-ink_text-muted uppercase">Status</div>
            <div class="mt-2"><x-ui.badge tone="success">{{ strtoupper(str_replace('_',' ',$customer->status)) }}</x-ui.badge></div>
        </x-ui.card>
    </div>

    <div class="flex gap-2 mb-5 border-b border-line">
        @foreach (['purchases' => 'Purchase History', 'orders' => 'Current Orders', 'installments' => 'Installment Scheme'] as $key => $label)
            <button wire:click="setTab('{{ $key }}')"
                class="pt-2.5 pb-2.5 px-1 mr-5 bg-transparent border-0 border-b-2 text-[13px] {{ $tab === $key ? 'border-gold text-gold font-bold' : 'border-transparent text-ink_text-secondary font-semibold' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    @if ($tab === 'purchases')
        <x-ui.card class="!p-0 overflow-hidden">
            <x-ui.table :headers="['Invoice', 'Date', 'Items', 'Total', 'Status']">
                @forelse ($sales as $sale)
                    <tr class="h-[60px] border-b border-line-light">
                        <td class="px-4">
                            <a href="{{ route('sales.invoice', $sale) }}" wire:navigate class="text-gold font-semibold">{{ $sale->invoice_number }}</a>
                        </td>
                        <td class="px-4 text-ink_text-primary">{{ $sale->created_at?->format('d M Y') }}</td>
                        <td class="px-4 text-ink_text-primary">{{ $sale->items->count() }}</td>
                        <td class="px-4 text-ink_text-primary">₹{{ number_format($sale->total, 2) }}</td>
                        <td class="px-4">
                            @if ($sale->confirmed_by_accountant)
                                <x-ui.badge tone="success">Confirmed</x-ui.badge>
                            @else
                                <x-ui.badge tone="warning">Reserved</x-ui.badge>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr class="h-[60px] border-b border-line-light">
                        <td colspan="5" class="px-4 text-ink_text-secondary">No purchases yet.</td>
                    </tr>
                @endforelse
            </x-ui.table>
        </x-ui.card>
    @endif

    @if ($tab === 'orders')
        <div class="bg-gold-soft/40 border border-line rounded-control p-3.5 text-sm text-gold-dark">
            Custom Orders has no backing table in the schema (flagged already for Section 5 — the whole module runs on
            sample data there). There's nothing real to show here per-customer until that table exists, so this tab is
            intentionally left as a note rather than a fabricated order list.
        </div>
    @endif

    @if ($tab === 'installments')
        <div class="flex flex-col gap-3.5">
            @forelse ($installmentSchemes as $scheme)
                <x-ui.card>
                    <div class="flex justify-between">
                        <span class="font-bold text-[13.5px] text-ink_text-primary">₹{{ number_format($scheme->monthly_amount,2) }}/month</span>
                        <x-ui.badge :tone="$scheme->status === 'active' ? 'success' : 'neutral'">{{ strtoupper($scheme->status) }}</x-ui.badge>
                    </div>
                    <div class="text-[11.5px] text-ink_text-secondary mt-1">
                        {{ $scheme->months_paid }} month(s) paid · started {{ \Illuminate\Support\Carbon::parse($scheme->start_date)->format('d M Y') }}
                    </div>
                    <div class="mt-2.5 border-t border-line pt-2.5">
                        @forelse ($scheme->payments as $payment)
                            <div class="flex justify-between text-xs py-1 text-ink_text-primary">
                                <span>{{ \Illuminate\Support\Carbon::parse($payment->paid_on)->format('d M Y') }}</span>
                                <span>₹{{ number_format($payment->amount,2) }}</span>
                            </div>
                        @empty
                            <span class="text-xs text-ink_text-secondary">No payments recorded yet.</span>
                        @endforelse
                    </div>
                </x-ui.card>
            @empty
                <div class="text-ink_text-secondary text-sm">No installment scheme for this customer.</div>
            @endforelse
        </div>
    @endif
</div>
