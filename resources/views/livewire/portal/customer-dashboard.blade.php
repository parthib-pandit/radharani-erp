<div style="min-height:100vh;background:#FAF8F4;font-family:'Public Sans',sans-serif;color:#211D19;">
    <div style="max-width:720px;margin:0 auto;padding:32px 20px 60px;">

        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
            <div>
                <div style="font-family:'Newsreader',Georgia,serif;font-size:22px;">Hello, {{ $customer->name }}</div>
                <div style="font-size:12px;color:#8B7F6F;margin-top:2px;">Loyalty points: <strong>{{ $customer->loyalty_points }}</strong></div>
            </div>
            <button wire:click="logout" style="background:none;border:1px solid #E7E0D4;border-radius:8px;padding:8px 16px;font-size:12.5px;cursor:pointer;">Log Out</button>
        </div>

        <div style="display:flex;gap:8px;margin-bottom:20px;border-bottom:1px solid #E7E0D4;">
            @foreach (['purchases' => 'Past Purchases', 'loyalty' => 'Loyalty & Referral', 'installments' => 'Installments'] as $key => $label)
                <button wire:click="setTab('{{ $key }}')"
                    style="padding:10px 4px;margin-right:20px;background:none;border:none;border-bottom:2px solid {{ $tab === $key ? '#A9772F' : 'transparent' }};color:{{ $tab === $key ? '#A9772F' : '#8B7F6F' }};font-weight:{{ $tab === $key ? '700' : '600' }};font-size:13px;cursor:pointer;">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        @if ($tab === 'purchases')
            <div style="display:flex;flex-direction:column;gap:12px;">
                @forelse ($customer->sales as $sale)
                    <div style="background:#fff;border:1px solid #E7E0D4;border-radius:10px;padding:16px;">
                        <div style="display:flex;justify-content:space-between;">
                            <span style="font-weight:700;font-size:13.5px;">Invoice {{ $sale->invoice_number }}</span>
                            <span style="font-size:13px;">₹{{ number_format($sale->total, 2) }}</span>
                        </div>
                        <div style="font-size:11.5px;color:#8B7F6F;margin-top:4px;">
                            {{ $sale->created_at?->format('d M Y') }} · {{ $sale->items->count() }} item(s)
                        </div>
                    </div>
                @empty
                    <div style="color:#8B7F6F;font-size:13px;">No purchases yet.</div>
                @endforelse
            </div>
        @endif

        @if ($tab === 'loyalty')
            <div style="background:#fff;border:1px solid #E7E0D4;border-radius:10px;padding:0;overflow:hidden;margin-bottom:20px;">
                <table style="width:100%;border-collapse:collapse;font-size:13px;">
                    <thead>
                        <tr style="text-align:left;border-bottom:1px solid #E7E0D4;">
                            <th style="padding:10px 16px;color:#8B7F6F;font-size:11px;text-transform:uppercase;">Date</th>
                            <th style="color:#8B7F6F;font-size:11px;text-transform:uppercase;">Reason</th>
                            <th style="padding-right:16px;color:#8B7F6F;font-size:11px;text-transform:uppercase;text-align:right;">Points</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customer->loyaltyTransactions as $tx)
                            <tr style="border-bottom:1px solid #F3EFE8;">
                                <td style="padding:10px 16px;">{{ $tx->created_at?->format('d M Y') }}</td>
                                <td>{{ ucfirst($tx->reason) }}</td>
                                <td style="padding-right:16px;text-align:right;color:{{ $tx->points >= 0 ? '#3F6B4A' : '#B04A3C' }};">
                                    {{ $tx->points >= 0 ? '+' : '' }}{{ $tx->points }}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" style="padding:16px;color:#8B7F6F;">No loyalty activity yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="background:#FBF3E6;border-radius:10px;padding:16px;">
                <div style="font-size:12.5px;color:#8A5F22;font-weight:700;">Your Referral Code</div>
                <div style="font-family:'Newsreader',Georgia,serif;font-size:20px;margin-top:4px;">{{ $customer->referral_code ?? '—' }}</div>
                <div style="font-size:11.5px;color:#8A5F22;margin-top:4px;">Share this — you earn bonus points when they make their first purchase.</div>
            </div>

            @if ($customer->referrals->count())
            <div style="margin-top:16px;background:#fff;border:1px solid #E7E0D4;border-radius:10px;padding:16px;">
                <div style="font-size:12.5px;font-weight:700;margin-bottom:10px;">People You've Referred</div>
                @foreach ($customer->referrals as $referred)
                    <div style="display:flex;justify-content:space-between;padding:6px 0;font-size:12.5px;border-bottom:1px solid #F3EFE8;">
                        <span>{{ $referred->name }}</span>
                        <span style="color:{{ $referred->sales_count > 0 ? '#3F6B4A' : '#8B7F6F' }};">
                            {{ $referred->sales_count > 0 ? 'Bonus earned' : 'Awaiting first purchase' }}
                        </span>
                    </div>
                @endforeach
            </div>
            @endif
        @endif

        @if ($tab === 'installments')
            <div style="display:flex;flex-direction:column;gap:14px;">
                @forelse ($customer->installmentSchemes as $scheme)
                    <div style="background:#fff;border:1px solid #E7E0D4;border-radius:10px;padding:16px;">
                        <div style="display:flex;justify-content:space-between;">
                            <span style="font-weight:700;font-size:13.5px;">₹{{ number_format($scheme->monthly_amount,2) }}/month</span>
                            <span class="rj-tag {{ $scheme->status === 'active' ? 'rj-tag-stock' : 'rj-tag-sold' }}" style="padding:3px 10px;border-radius:20px;font-size:10.5px;font-weight:700;">
                                {{ strtoupper($scheme->status) }}
                            </span>
                        </div>
                        <div style="font-size:11.5px;color:#8B7F6F;margin-top:4px;">
                            {{ $scheme->months_paid }} month(s) paid · started {{ $scheme->start_date?->format('d M Y') }}
                        </div>
                        <div style="margin-top:10px;border-top:1px solid #F3EFE8;padding-top:10px;">
                            @foreach ($scheme->payments as $payment)
                                <div style="display:flex;justify-content:space-between;font-size:12px;padding:4px 0;">
                                    <span>{{ $payment->paid_on?->format('d M Y') }}</span>
                                    <span>₹{{ number_format($payment->amount,2) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div style="color:#8B7F6F;font-size:13px;">No installment schemes yet.</div>
                @endforelse
            </div>
        @endif

    </div>
</div>
