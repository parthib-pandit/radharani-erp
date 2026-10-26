<x-ui.modal wire:model="showCancel" title="Cancel this sale" icon="x-circle" max-width="md" submit="confirmCancel"
    subtitle="The pieces go back into stock. This can not be undone, but the sale stays on record.">
    <ul class="mb-4 space-y-1.5 text-[12.5px] text-ink_text-secondary list-disc pl-5">
        <li>Exchange credit and advance the sale used go back to the customer.</li>
        <li>Money paid in cash, UPI, card, bank or cheque is held on the customer's account as an advance, so it can be paid back or used later.</li>
        <li>A custom order this sale was delivering is open again.</li>
    </ul>
    <x-ui.field label="Why is it cancelled?" for="cs-reason" error="cancelReason">
        <input id="cs-reason" type="text" wire:model="cancelReason" maxlength="255" class="rj-input @error('cancelReason') is-invalid @enderror" autocomplete="off" autofocus>
    </x-ui.field>
    <x-slot:footer>
        <x-ui.button variant="secondary" x-on:click="show = false">Keep the sale</x-ui.button>
        <x-ui.button type="submit" variant="danger" target="confirmCancel" icon="x">Cancel sale</x-ui.button>
    </x-slot:footer>
</x-ui.modal>
