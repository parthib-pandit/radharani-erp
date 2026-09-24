<div>
    <x-ui.page-header title="Referral Overview" subtitle="Who's referring customers, and whether the bonus has actually been earned." />

    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search referrer name..."
        class="rj-input mb-4 w-[280px]">

    <div class="flex flex-col gap-3.5">
        @forelse ($referrers as $referrer)
            <x-ui.card>
                <div class="flex justify-between items-center">
                    <div>
                        <span class="font-bold text-sm text-ink_text-primary">{{ $referrer->name }}</span>
                        <span class="text-[11.5px] text-ink_text-secondary ml-2">code: {{ $referrer->referral_code }}</span>
                    </div>
                    <x-ui.badge tone="success">{{ $referrer->referrals_count }} REFERRED</x-ui.badge>
                </div>

                <div class="mt-3">
                    <x-ui.table :headers="['Referred Customer', 'Purchases', 'Bonus Earned']">
                        @foreach ($referrer->referrals as $referred)
                            <tr class="h-[60px] border-b border-line-light">
                                <td class="px-4 text-ink_text-primary">{{ $referred->name }}</td>
                                <td class="px-4 text-ink_text-primary">{{ $referred->sales_count }}</td>
                                <td class="px-4">
                                    <x-ui.badge :tone="$referred->sales_count > 0 ? 'success' : 'warning'">
                                        {{ $referred->sales_count > 0 ? 'YES' : 'PENDING FIRST SALE' }}
                                    </x-ui.badge>
                                </td>
                            </tr>
                        @endforeach
                    </x-ui.table>
                </div>
            </x-ui.card>
        @empty
            <div class="text-ink_text-secondary text-sm">No referrals recorded yet.</div>
        @endforelse
    </div>

    <div class="mt-4">{{ $referrers->links() }}</div>
</div>
