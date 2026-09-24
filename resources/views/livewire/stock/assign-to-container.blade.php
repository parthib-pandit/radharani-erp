<div>
    <x-ui.page-header title="Assign Items to Packet" subtitle="Move items into a packet using whichever method is fastest right now." />

    @if (session('message'))
        <div class="bg-success-bg text-success rounded-control px-3.5 py-2.5 mb-5 text-sm">{{ session('message') }}</div>
    @endif

    <div class="flex gap-1 bg-surface-muted rounded-control p-1 mb-[22px] max-w-[480px]">
        @foreach (['scan' => 'Scan', 'manual' => 'Search & Select', 'spreadsheet' => 'Upload Spreadsheet'] as $key => $label)
            <button wire:click="setMode('{{ $key }}')"
                class="flex-1 h-9 rounded-md text-[12.5px] font-bold cursor-pointer {{ $mode === $key ? 'bg-white text-ink_text-primary shadow-card' : 'bg-transparent text-ink_text-secondary' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    @if ($mode === 'scan')
    <x-ui.card class="max-w-[480px]">
        <div class="font-semibold text-[13.5px] text-ink_text-primary mb-3.5">Scan item, then scan packet</div>
        @if ($scanResult)
            <div class="bg-success-bg text-success rounded-control px-3.5 py-2.5 mb-4 text-sm">{{ $scanResult }}</div>
        @endif
        @if ($scanError)
            <div class="bg-danger-bg text-danger rounded-control px-3.5 py-2.5 mb-4 text-sm">{{ $scanError }}</div>
        @endif
        <form wire:submit="scanAssign">
            <label class="block text-[11.5px] text-ink_text-secondary mb-1">1. Scan or type item code</label>
            <input type="text" wire:model="scanItemCode" autofocus placeholder="HUID or internal code" class="rj-input w-full mb-3.5">
            <label class="block text-[11.5px] text-ink_text-secondary mb-1">2. Scan or type packet code</label>
            <input type="text" wire:model="scanPacketCode" placeholder="Packet code" class="rj-input w-full mb-[18px]">
            <x-ui.button type="submit" variant="primary" class="w-full">Assign</x-ui.button>
        </form>
    </x-ui.card>
    @endif

    @if ($mode === 'manual')
    <x-ui.card class="max-w-[640px]">
        <div class="font-semibold text-[13.5px] text-ink_text-primary mb-3.5">Search for an item, then choose its packet</div>
        <input type="text" wire:model.live.debounce.300ms="manualSearch" placeholder="Search by HUID, code or category..." class="rj-input w-full mb-3">

        @if ($manualSearch && $searchResults->isNotEmpty())
        <div class="border border-line rounded-control overflow-hidden mb-4">
            @foreach ($searchResults as $result)
                <div wire:click="$set('manualItemId', {{ $result->id }})"
                    class="px-3.5 py-2.5 text-[12.5px] cursor-pointer border-b border-line-light {{ $manualItemId === $result->id ? 'bg-gold-soft/40' : 'bg-white' }}">
                    <strong>{{ $result->huid_code ?: $result->internal_code }}</strong> — {{ $result->category }}, {{ $result->weight }}g
                    @if ($manualItemId === $result->id) <span class="text-gold float-right font-bold">Selected</span> @endif
                </div>
            @endforeach
        </div>
        @endif

        <label class="block text-[11.5px] text-ink_text-secondary mb-1">Assign to packet</label>
        <select wire:model="manualPacketId" class="rj-select w-full mb-[18px]">
            <option value="">— select packet —</option>
            @foreach ($packets as $packet)
                <option value="{{ $packet->id }}">{{ $packet->code }} — {{ $packet->label }}</option>
            @endforeach
        </select>

        <x-ui.button wire:click="manualAssign" variant="primary" {{ (!$manualItemId || !$manualPacketId) ? 'disabled' : '' }}>Assign Item</x-ui.button>
    </x-ui.card>
    @endif

    @if ($mode === 'spreadsheet')
    <x-ui.card class="max-w-[560px]">
        <div class="font-semibold text-[13.5px] text-ink_text-primary mb-3.5">Upload a spreadsheet to assign many at once</div>
        <div class="text-xs text-ink_text-secondary mb-3.5">Columns expected: item code, packet code. .csv or .xlsx.</div>
        <input type="file" wire:model="spreadsheet" class="rj-input w-full mb-2.5">
        @error('spreadsheet') <div class="text-danger text-[11.5px] mb-2.5">{{ $message }}</div> @enderror
        <x-ui.button wire:click="previewSheet" variant="primary" {{ !$spreadsheet ? 'disabled' : '' }}>Preview File</x-ui.button>

        @if ($sheetPreviewed)
        <div class="mt-[18px] pt-4 border-t border-line">
            <div class="bg-warning-bg text-warning rounded-control px-3.5 py-2.5 text-[12.5px]">
                File received — row-by-row parsing and duplicate detection lands with backend integration. For now, use Scan or Search &amp; Select above to assign items.
            </div>
        </div>
        @endif
    </x-ui.card>
    @endif
</div>
