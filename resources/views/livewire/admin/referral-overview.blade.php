<x-layouts.app title="Referrals — Radharani Jewellery">
<div>
    <div class="rj-serif" style="font-size:24px;margin-bottom:4px;">Referral Overview</div>
    <div style="font-size:13px;color:var(--muted);margin-bottom:24px;">Who's referring customers, and whether the bonus has actually been earned.</div>

    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search referrer name..."
        class="rj-input" style="margin-bottom:16px;width:280px;">

    <div style="display:flex;flex-direction:column;gap:14px;">
        @forelse ($referrers as $referrer)
            <div class="rj-card">
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <div>
                        <span style="font-weight:700;font-size:14px;">{{ $referrer->name }}</span>
                        <span style="font-size:11.5px;color:var(--muted);margin-left:8px;">code: {{ $referrer->referral_code }}</span>
                    </div>
                    <span class="rj-tag rj-tag-stock">{{ $referrer->referrals_count }} REFERRED</span>
                </div>

                <table class="rj-table" style="margin-top:12px;">
                    <thead>
                        <tr><th>Referred Customer</th><th>Purchases</th><th>Bonus Earned</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($referrer->referrals as $referred)
                            <tr>
                                <td>{{ $referred->name }}</td>
                                <td>{{ $referred->sales_count }}</td>
                                <td>
                                    <span class="rj-tag {{ $referred->sales_count > 0 ? 'rj-tag-stock' : 'rj-tag-dispatched' }}">
                                        {{ $referred->sales_count > 0 ? 'YES' : 'PENDING FIRST SALE' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @empty
            <div style="color:var(--muted);font-size:13px;">No referrals recorded yet.</div>
        @endforelse
    </div>

    <div style="margin-top:16px;">{{ $referrers->links() }}</div>
</div>
</x-layouts.app>
