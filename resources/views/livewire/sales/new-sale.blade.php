<div>
    <x-ui.page-header title="New Sale / Billing" subtitle="Submitting reserves this sale — it isn't final until admin verifies it." />

    <div class="bg-warning-bg text-warning rounded-control px-3.5 py-2.5 mb-5 max-w-[900px] text-xs">
        Flag: <code>sales.invoice_number</code> must be set at creation, but the spec wants it assigned only on verification — and rule 1 forbids updating a sales row afterward. A placeholder "RESV-…" number is used below until verification assigns the real one.
    </div>

    @if ($result)<div class="bg-success-bg text-success rounded-control px-3.5 py-2.5 mb-5 max-w-[900px] text-sm">{{ $result }}</div>@endif

    <div class="grid grid-cols-[1.3fr_1fr] gap-5 max-w-[1100px]">
        <div>
            <x-ui.card class="mb-4">
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Customer</label>
                <input type="text" wire:model.live.debounce.300ms="customerSearch" placeholder="Search name / phone..." class="rj-input w-full">
                @if ($customerSearch && $customerResults->isNotEmpty())
                <div class="border border-line rounded-lg overflow-hidden mt-2">
                    @foreach ($customerResults as $c)
                    <div wire:click="$set('customerId', {{ $c->id }})" class="px-3 py-2 text-[12.5px] cursor-pointer border-b border-line-light {{ $customerId === $c->id ? 'bg-gold-soft/40' : 'bg-white' }}">
                        {{ $c->name }} — {{ $c->phone }} · {{ $c->loyalty_points }} pts
                    </div>
                    @endforeach
                </div>
                @endif
                @error('customerId') <div class="text-danger text-[11px] mt-1">Select a customer.</div> @enderror
            </x-ui.card>

            <x-ui.card>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Add item — scan or search</label>
                <input type="text" wire:model.live.debounce.300ms="itemSearch" placeholder="HUID / code / category..." class="rj-input w-full">
                @if ($itemSearch && $itemResults->isNotEmpty())
                <div class="border border-line rounded-lg overflow-hidden mt-2">
                    @foreach ($itemResults as $r)
                    <div wire:click="addItem({{ $r->id }})" class="px-3 py-2 text-[12.5px] cursor-pointer border-b border-line-light">
                        {{ $r->huid_code ?: $r->internal_code }} — {{ $r->category }}, {{ $r->weight }}g
                    </div>
                    @endforeach
                </div>
                @endif
                @error('cart') <div class="text-danger text-[11px] mt-1.5">{{ $message }}</div> @enderror

                <x-ui.table :headers="['Item', 'Live Price', 'GST', '']">
                    @forelse ($cart as $itemId => $line)
                    <tr class="h-[60px] border-b border-line-light">
                        <td class="px-4 font-semibold text-ink_text-primary">{{ $line['label'] }} <span class="text-ink_text-secondary font-normal">({{ $line['category'] }})</span></td>
                        <td class="px-4 text-ink_text-primary">₹{{ number_format($line['price'],2) }}</td>
                        <td class="px-4 text-ink_text-primary">{{ $line['gst_rate'] }}%</td>
                        <td class="px-4"><button wire:click="removeItem({{ $itemId }})" class="bg-transparent border-0 text-danger text-xs cursor-pointer">Remove</button></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-4 py-4 text-ink_text-secondary">No items added yet — prices are always computed live, never typed.</td></tr>
                    @endforelse
                </x-ui.table>
            </x-ui.card>
        </div>

        <div>
            <x-ui.card class="mb-4">
                <div class="font-bold text-[13.5px] mb-3">Totals</div>
                <div class="flex justify-between text-[12.5px] py-1"><span>Subtotal</span><span>₹{{ number_format($this->subtotal,2) }}</span></div>
                <div class="flex justify-between text-[12.5px] py-1"><span>GST (CGST+SGST)</span><span>₹{{ number_format($this->gstTotal,2) }}</span></div>
                <div class="flex justify-between text-[12.5px] py-1 text-success"><span>Loyalty discount</span><span>-₹{{ number_format($this->loyaltyDiscount,2) }}</span></div>
                <div class="flex justify-between text-base font-bold border-t border-line pt-2.5 mt-1.5"><span>Grand Total</span><span>₹{{ number_format($this->grandTotal,2) }}</span></div>
            </x-ui.card>

            <x-ui.card class="mb-4">
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Apply loyalty points</label>
                <input type="number" wire:model.live="loyaltyPointsUsed" class="rj-input w-full">
                <div class="text-[11px] text-ink_text-secondary mt-1">Customer balance: {{ $this->customerObject?->loyalty_points ?? 0 }} pts</div>
            </x-ui.card>

            <x-ui.card>
                <div class="font-bold text-[13px] mb-2.5">Payment (can combine modes)</div>
                @foreach ($paymentModes as $i => $pm)
                <div class="flex gap-2 mb-2">
                    <select wire:model="paymentModes.{{ $i }}.mode" class="rj-select flex-1">
                        <option value="cash">Cash</option>
                        <option value="bank">Bank</option>
                        <option value="upi">UPI</option>
                        <option value="card">Card</option>
                    </select>
                    <input type="number" step="0.01" wire:model="paymentModes.{{ $i }}.amount" placeholder="₹" class="rj-input w-[110px]">
                </div>
                @endforeach
                <x-ui.button type="button" wire:click="addPaymentMode" variant="secondary" class="w-full mb-3.5">+ Add Payment Mode</x-ui.button>

                <x-ui.button wire:click="submit" variant="primary" class="w-full">Reserve Sale</x-ui.button>
            </x-ui.card>
        </div>
    </div>
</div>
