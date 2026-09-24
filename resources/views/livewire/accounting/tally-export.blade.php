<div>
    <x-ui.page-header title="Tally-Compatible Export" subtitle="Exports the ledger as CSV for the chosen date range." />

    <x-ui.card class="max-w-[480px]">
        <div class="grid grid-cols-2 gap-3 mb-4">
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">From</label>
                <input type="date" wire:model="fromDate" class="rj-input w-full">
                @error('fromDate') <div class="text-danger text-[11px] mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">To</label>
                <input type="date" wire:model="toDate" class="rj-input w-full">
                @error('toDate') <div class="text-danger text-[11px] mt-1">{{ $message }}</div> @enderror
            </div>
        </div>
        <x-ui.button type="button" wire:click="download" variant="primary">Download CSV</x-ui.button>
    </x-ui.card>

    <div class="mt-4 bg-[#FFF7E6] border border-line rounded-control p-3 text-sm text-gold-dark max-w-[480px]">
        This is a plain CSV of the ledger, not a true Tally XML voucher import — there's no ledger-name mapping table in
        the schema yet to translate accounts into Tally's own ledger names. Flagging that as a follow-up rather than
        guessing a mapping.
    </div>
</div>
