<div>
    <x-ui.page-header title="Bulk Import Customers" subtitle="Upload a CSV with columns: name, phone, address, email, gstin. Only name and phone are required. Duplicate phone numbers are skipped, not overwritten." />

    <x-ui.card class="max-w-[520px] mb-5">
        <form wire:submit="import">
            <div class="mb-3.5">
                <input type="file" wire:model="file" accept=".csv,.txt">
                @error('file') <div class="text-danger text-xs mt-1.5">{{ $message }}</div> @enderror
            </div>
            <div class="flex items-center gap-2">
                <x-ui.button type="submit" variant="primary" wire:loading.attr="disabled">Import</x-ui.button>
                <span wire:loading class="text-xs text-ink_text-secondary">Processing…</span>
            </div>
        </form>
    </x-ui.card>

    @if ($done)
        <x-ui.card class="max-w-[640px] mb-4">
            <div class="font-bold text-sm mb-2 text-success">Imported ({{ count($imported) }})</div>
            @forelse ($imported as $row)
                <div class="text-[12.5px] py-[3px] text-ink_text-primary">{{ $row }}</div>
            @empty
                <div class="text-[12.5px] text-ink_text-secondary">None.</div>
            @endforelse
        </x-ui.card>

        <x-ui.card class="max-w-[640px]">
            <div class="font-bold text-sm mb-2 text-danger">Skipped ({{ count($skipped) }})</div>
            @forelse ($skipped as $row)
                <div class="text-[12.5px] py-[3px] text-ink_text-primary">{{ $row }}</div>
            @empty
                <div class="text-[12.5px] text-ink_text-secondary">None.</div>
            @endforelse
        </x-ui.card>
    @endif

    <div class="mt-5">
        <a href="{{ route('admin.customers') }}" wire:navigate class="text-[12.5px] text-gold">&larr; Back to Customers</a>
    </div>
</div>
