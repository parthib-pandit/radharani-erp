<div>
    @php
        $inr = fn ($n) => '₹' . number_format(abs($n), 2);
        $dateLabel = \Illuminate\Support\Carbon::parse($date)->format('l, j M Y');
        $close = $day['close'];
    @endphp

    <x-ui.page-header title="Cash Book" subtitle="Money in and out of the vault cash and the petty cash. Nothing is edited: a mistake is put right with a new entry."
        :crumbs="[['label' => 'Cash Book']]" />

    {{-- The two books --}}
    <div class="rj-segment mb-5 max-w-full overflow-x-auto" role="tablist">
        @foreach ($books as $key => $label)
            <button type="button" role="tab" wire:click="setBook('{{ $key }}')" class="{{ $book === $key ? 'is-active' : '' }}">
                {{ $label }} <span class="ml-1.5 tabular font-semibold">{{ $inr($balances[$key]) }}</span>
            </button>
        @endforeach
    </div>

    {{-- Which day --}}
    <div class="flex flex-wrap items-center gap-2 mb-5">
        <x-ui.button variant="secondary" size="icon" icon="chevron-left" wire:click="shiftDay(-1)" aria-label="The day before" />
        <input type="date" wire:model.live="date" max="{{ now()->toDateString() }}" class="rj-input w-[170px]" aria-label="Day">
        <x-ui.button variant="secondary" size="icon" icon="chevron-right" wire:click="shiftDay(1)" :disabled="$isToday" aria-label="The day after" />
        @unless ($isToday)<x-ui.button variant="ghost" size="sm" wire:click="goToday">Today</x-ui.button>@endunless
        <span class="text-[13px] text-ink_text-secondary ml-1">{{ $dateLabel }}</span>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-5">
        <x-ui.stat-card icon="wallet" label="Started the day with" :value="$inr($day['opening'])" />
        <x-ui.stat-card icon="arrow-down-left" label="Money in" :value="$inr($day['in'])" />
        <x-ui.stat-card icon="arrow-up-right" label="Money out" :value="$inr($day['out'])" />
        <x-ui.stat-card icon="coins" label="Ended with" :value="$inr($day['closing'])" />
    </div>

    <div class="flex flex-wrap gap-2 mb-5">
        <x-ui.button icon="plus" wire:click="startEntry('receipt')">Money in</x-ui.button>
        <x-ui.button variant="secondary" icon="minus" wire:click="startEntry('payment')">Money out</x-ui.button>
        <x-ui.button variant="secondary" icon="repeat" wire:click="startTransfer">Move to {{ $otherBook }}</x-ui.button>
        <x-ui.button variant="soft" icon="check-circle" wire:click="startClose">Count and close the day</x-ui.button>
        @unless ($hasOpening)
            <x-ui.button variant="ghost" icon="flag" wire:click="startOpening">Set the opening balance</x-ui.button>
        @endunless
    </div>

    @if ($close)
        @php $diff = $close->difference; @endphp
        <div @class(['mb-5 flex flex-wrap items-center gap-x-4 gap-y-1 px-4 py-3 rounded-card border text-[13px]', 'bg-success-bg border-success/20 text-success' => abs($diff) < 0.005, 'bg-warning-bg border-warning/20 text-warning' => abs($diff) >= 0.005])>
            <x-ui.icon :name="abs($diff) < 0.005 ? 'check-circle' : 'alert-triangle'" :size="16" class="shrink-0" />
            <span class="font-semibold">Day closed by {{ $close->user?->name }} at {{ $close->created_at->format('g:i a') }}.</span>
            <span>Counted {{ $inr($close->counted) }}, the book said {{ $inr($close->expected) }}.
                @if (abs($diff) < 0.005) It matches. @else {{ $diff > 0 ? 'Over by ' : 'Short by ' }}{{ $inr($diff) }}. @endif</span>
            @if ($close->note)<span class="text-ink_text-secondary">{{ $close->note }}</span>@endif
            @if (abs($close->expected - $day['closing']) > 0.005)
                <span class="text-danger font-semibold">The book has changed since: it now says {{ $inr($day['closing']) }}.</span>
            @endif
        </div>
    @endif

    <x-ui.card :padding="false" title="{{ $books[$book] }}" subtitle="{{ $dateLabel }}" icon="wallet">
        @if ($day['lines']->isEmpty())
            <x-ui.empty-state icon="wallet" title="Nothing recorded this day" message="Money in and out will show here, with the balance after each line." compact />
        @else
            <x-ui.table :headers="['Time', 'What', 'By', ['label' => 'In', 'class' => 'text-right'], ['label' => 'Out', 'class' => 'text-right'], ['label' => 'Balance', 'class' => 'text-right'], ['label' => '', 'class' => 'w-px']]">
                @foreach ($day['lines'] as $l)
                    <tr wire:key="cl-{{ $l['source'] }}-{{ $l['id'] ?? $loop->index }}" @class(['opacity-60' => $l['reversed']])>
                        <td class="whitespace-nowrap text-[12.5px] text-ink_text-secondary tabular">{{ $l['at']->format('g:i a') }}</td>
                        <td class="min-w-[200px]">
                            <span @class(['line-through' => $l['reversed']])>
                                @if ($l['url'])<a href="{{ $l['url'] }}" class="hover:text-gold-dark">{{ $l['label'] }}</a>@else{{ $l['label'] }}@endif
                            </span>
                            @if ($l['source'] === 'auto')<x-ui.badge size="sm" tone="info" class="ml-1.5">Automatic</x-ui.badge>@endif
                            @if ($l['reverses'])<x-ui.badge size="sm" tone="warning" class="ml-1.5">Correction</x-ui.badge>@endif
                            @if ($l['reversed'])<x-ui.badge size="sm" class="ml-1.5">Put right</x-ui.badge>@endif
                            @if ($l['kind'] === 'opening')<x-ui.badge size="sm" tone="gold" class="ml-1.5">Opening</x-ui.badge>@endif
                            @if ($l['note'])<span class="block text-[12px] text-ink_text-muted">{{ $l['note'] }}</span>@endif
                        </td>
                        <td class="text-[12.5px] text-ink_text-secondary whitespace-nowrap">{{ $l['user'] ?? '-' }}</td>
                        <td class="text-right tabular text-success">{{ $l['amount'] > 0 ? $inr($l['amount']) : '' }}</td>
                        <td class="text-right tabular text-danger">{{ $l['amount'] < 0 ? $inr($l['amount']) : '' }}</td>
                        <td class="text-right tabular font-semibold">{{ $inr($l['balance']) }}</td>
                        <td>
                            @if ($l['source'] === 'manual' && ! $l['reversed'] && ! $l['reverses'] && ! $l['transfer'] && $l['kind'] !== 'opening')
                                <x-ui.button variant="ghost" size="xs" wire:click="startReverse({{ $l['id'] }})" title="Put this right with a new entry">Put right</x-ui.button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </x-ui.table>
        @endif
    </x-ui.card>
    @if ($book === 'main')
        <p class="rj-help mt-3">Cash taken on sales, cash advances on orders and cash advances from customers show here on their own and are marked Automatic. Cash taken when an order is placed, cash paid to karigars and cash paid out for an exchange are not picked up yet: enter them by hand.</p>
    @endif

    <x-ui.card :padding="false" title="Days closed" subtitle="The last ten counts of {{ $books[$book] }}" icon="check-circle" class="mt-6">
        @if ($recentCloses->isEmpty())
            <x-ui.empty-state icon="check-circle" title="No day closed yet" message="Count the notes at the end of the day and close it. Any difference is kept." compact />
        @else
            <x-ui.table :headers="['Day', ['label' => 'Book said', 'class' => 'text-right'], ['label' => 'Counted', 'class' => 'text-right'], ['label' => 'Difference', 'class' => 'text-right'], 'By']">
                @foreach ($recentCloses as $c)
                    <tr wire:key="close-{{ $c->id }}">
                        <td><button type="button" wire:click="$set('date', '{{ $c->closed_on->toDateString() }}')" class="hover:text-gold-dark">{{ $c->closed_on->format('D, j M Y') }}</button></td>
                        <td class="text-right tabular">{{ $inr($c->expected) }}</td>
                        <td class="text-right tabular">{{ $inr($c->counted) }}</td>
                        <td class="text-right tabular font-semibold {{ abs($c->difference) < 0.005 ? 'text-success' : 'text-warning' }}">{{ abs($c->difference) < 0.005 ? 'Matches' : ($c->difference > 0 ? '+' : '-') . $inr($c->difference) }}</td>
                        <td class="text-[12.5px] text-ink_text-secondary">{{ $c->user?->name }}</td>
                    </tr>
                @endforeach
            </x-ui.table>
        @endif
    </x-ui.card>

    {{-- Money in or out --}}
    <x-ui.modal wire:model="showEntry" :title="$entryKind === 'receipt' ? 'Money in' : 'Money out'" icon="wallet" max-width="md" submit="saveEntry"
        :subtitle="$books[$book] . ' · ' . $dateLabel">
        <div class="space-y-4">
            <x-ui.field label="Amount" for="ce-amount" error="entryAmount">
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-[13px] text-ink_text-muted pointer-events-none">₹</span>
                    <input id="ce-amount" type="number" step="0.01" min="0" wire:model="entryAmount" class="rj-input pl-8 tabular @error('entryAmount') is-invalid @enderror" inputmode="decimal" autofocus>
                </div>
            </x-ui.field>
            <x-ui.field :label="$entryKind === 'receipt' ? 'Received from, or why' : 'Paid to, or for what'" for="ce-reason" error="entryReason">
                <input id="ce-reason" type="text" wire:model="entryReason" maxlength="255" class="rj-input @error('entryReason') is-invalid @enderror" autocomplete="off">
            </x-ui.field>
            <x-ui.field label="Note" for="ce-note" error="entryNote" optional>
                <input id="ce-note" type="text" wire:model="entryNote" maxlength="255" class="rj-input" autocomplete="off">
            </x-ui.field>
        </div>
        <x-slot:footer>
            <x-ui.button variant="secondary" x-on:click="show = false">Cancel</x-ui.button>
            <x-ui.button type="submit" target="saveEntry" icon="check">Record it</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>

    {{-- Between the books --}}
    <x-ui.modal wire:model="showTransfer" :title="'Move to ' . $otherBook" icon="repeat" max-width="md" submit="saveTransfer"
        :subtitle="'Out of ' . $books[$book] . ' and into ' . $otherBook . ', as two linked entries.'">
        <div class="space-y-4">
            <x-ui.field label="Amount" for="ct-amount" error="transferAmount">
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-[13px] text-ink_text-muted pointer-events-none">₹</span>
                    <input id="ct-amount" type="number" step="0.01" min="0" wire:model="transferAmount" class="rj-input pl-8 tabular @error('transferAmount') is-invalid @enderror" inputmode="decimal" autofocus>
                </div>
            </x-ui.field>
            <x-ui.field label="Why" for="ct-reason" error="transferReason">
                <input id="ct-reason" type="text" wire:model="transferReason" maxlength="255" class="rj-input @error('transferReason') is-invalid @enderror" autocomplete="off">
            </x-ui.field>
        </div>
        <x-slot:footer>
            <x-ui.button variant="secondary" x-on:click="show = false">Cancel</x-ui.button>
            <x-ui.button type="submit" target="saveTransfer" icon="check">Move it</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>

    {{-- Opening balance --}}
    <x-ui.modal wire:model="showOpening" title="Opening balance" icon="flag" max-width="md" submit="saveOpening"
        :subtitle="'What ' . $books[$book] . ' held on ' . $dateLabel . ', before anything below. It can be set once.'">
        <x-ui.field label="Amount" for="co-amount" error="openingAmount">
            <div class="relative">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-[13px] text-ink_text-muted pointer-events-none">₹</span>
                <input id="co-amount" type="number" step="0.01" min="0" wire:model="openingAmount" class="rj-input pl-8 tabular @error('openingAmount') is-invalid @enderror" inputmode="decimal" autofocus>
            </div>
        </x-ui.field>
        <x-slot:footer>
            <x-ui.button variant="secondary" x-on:click="show = false">Cancel</x-ui.button>
            <x-ui.button type="submit" target="saveOpening" icon="check">Set it</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>

    {{-- Put an entry right --}}
    <x-ui.modal wire:model="showReverse" title="Put this entry right" icon="repeat" max-width="md" submit="saveReverse"
        subtitle="The entry stays on record. A new entry the other way round cancels it out, dated today.">
        <x-ui.field label="What was wrong" for="cr-reason" error="reverseReason">
            <input id="cr-reason" type="text" wire:model="reverseReason" maxlength="200" class="rj-input @error('reverseReason') is-invalid @enderror" autocomplete="off" autofocus>
        </x-ui.field>
        <x-slot:footer>
            <x-ui.button variant="secondary" x-on:click="show = false">Keep it</x-ui.button>
            <x-ui.button type="submit" variant="danger" target="saveReverse" icon="check">Put it right</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>

    {{-- Count and close the day --}}
    <x-ui.modal wire:model="showClose" title="Count and close the day" icon="check-circle" max-width="md" submit="saveClose"
        :subtitle="$books[$book] . ' · ' . $dateLabel . '. The book says ' . $inr($day['closing']) . '.'">
        <div class="grid grid-cols-[1fr_110px_110px] gap-x-3 gap-y-2 items-center text-[13px]">
            <span class="text-[11.5px] font-semibold uppercase tracking-wide text-ink_text-muted">Note or coin</span>
            <span class="text-[11.5px] font-semibold uppercase tracking-wide text-ink_text-muted text-right">How many</span>
            <span class="text-[11.5px] font-semibold uppercase tracking-wide text-ink_text-muted text-right">Amount</span>
            @foreach ($denominations as $d)
                <label for="cd-{{ $d }}" class="font-semibold tabular">₹{{ number_format($d) }}</label>
                <input id="cd-{{ $d }}" type="number" min="0" step="1" wire:model.live.debounce.250ms="counts.{{ $d }}" class="rj-input rj-input-sm tabular text-right" inputmode="numeric" aria-label="How many ₹{{ $d }}">
                <span class="text-right tabular text-ink_text-secondary">{{ number_format(max(0, (int) ($counts[$d] ?? 0)) * $d) }}</span>
            @endforeach
        </div>
        @php $diff = $this->counted - $day['closing']; @endphp
        <dl class="mt-4 pt-3 border-t border-line-light space-y-1.5 text-[13.5px]">
            <div class="flex justify-between"><dt class="text-ink_text-secondary">Counted</dt><dd class="tabular font-semibold">{{ $inr($this->counted) }}</dd></div>
            <div class="flex justify-between"><dt class="text-ink_text-secondary">The book says</dt><dd class="tabular">{{ $inr($day['closing']) }}</dd></div>
            <div class="flex justify-between font-semibold {{ abs($diff) < 0.005 ? 'text-success' : 'text-warning' }}"><dt>Difference</dt><dd class="tabular">{{ abs($diff) < 0.005 ? 'Matches' : ($diff > 0 ? 'Over by ' : 'Short by ') . $inr($diff) }}</dd></div>
        </dl>
        <x-ui.field label="Note" for="cc-note" error="closeNote" optional class="mt-4">
            <input id="cc-note" type="text" wire:model="closeNote" maxlength="255" class="rj-input" autocomplete="off" placeholder="If there is a difference, say why">
        </x-ui.field>
        <x-slot:footer>
            <x-ui.button variant="secondary" x-on:click="show = false">Cancel</x-ui.button>
            <x-ui.button type="submit" target="saveClose" icon="check">Close the day</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
</div>
