<div>
    <x-ui.page-header title="Bulk Import" subtitle="Upload a spreadsheet, map its columns, then review before anything is created." />

    <div class="flex gap-0 mb-7 max-w-[640px]">
        @foreach (['1' => 'Upload', '2' => 'Map Columns', '3' => 'Review', '4' => 'Done'] as $n => $label)
            <div class="flex-1 text-center">
                <div class="w-[26px] h-[26px] rounded-full mx-auto mb-1.5 flex items-center justify-center text-xs font-bold {{ $step >= $n ? 'bg-gold text-white' : 'bg-surface-muted text-ink_text-secondary' }}">{{ $n }}</div>
                <div class="text-[11px] {{ $step >= $n ? 'text-ink_text-primary font-bold' : 'text-ink_text-secondary font-semibold' }}">{{ $label }}</div>
            </div>
        @endforeach
    </div>

    @if ($step === 1)
    <x-ui.card class="max-w-[520px]">
        <div class="font-semibold text-[13.5px] text-ink_text-primary mb-3.5">Step 1 — Upload the file</div>
        <div class="text-xs text-ink_text-secondary mb-3.5">.csv, first row must be column headers.</div>
        <input type="file" wire:model="file" class="rj-input w-full mb-2.5">
        @error('file') <div class="text-danger text-[11.5px] mb-2.5">{{ $message }}</div> @enderror
        <x-ui.button wire:click="uploadFile" variant="primary" {{ !$file ? 'disabled' : '' }}>Continue</x-ui.button>
    </x-ui.card>
    @endif

    @if ($step === 2)
    <x-ui.card class="max-w-[640px]">
        <div class="font-semibold text-[13.5px] text-ink_text-primary mb-1">Step 2 — Match your columns</div>
        <div class="text-xs text-ink_text-secondary mb-4">{{ count($rows) }} rows found. We guessed some matches — check them before continuing.</div>
        <x-ui.table :headers="['Your column', 'System field']">
            @foreach ($headers as $h)
                <tr class="h-[60px] border-b border-line-light">
                    <td class="px-4 font-semibold text-ink_text-primary">{{ $h }}</td>
                    <td class="px-4">
                        <select wire:model="mapping.{{ $h }}" class="rj-select w-[220px]">
                            <option value="">— ignore this column —</option>
                            @foreach ($systemFields as $field => $label)
                                <option value="{{ $field }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
            @endforeach
        </x-ui.table>
        <x-ui.button wire:click="confirmMapping" variant="primary" class="mt-[18px]">Continue to Review</x-ui.button>
    </x-ui.card>
    @endif

    @if ($step === 3)
    <x-ui.card class="max-w-[760px]">
        <div class="font-semibold text-[13.5px] text-ink_text-primary mb-1">Step 3 — Review before import</div>
        <div class="text-xs text-ink_text-secondary mb-4">
            Rows flagged in red match an existing HUID and are unchecked by default — tick to import anyway.
        </div>
        <x-ui.table :headers="['', 'HUID', 'Category', 'Purity', 'Weight', '']">
            @foreach ($reviewRows as $i => $row)
                <tr class="h-[60px] border-b border-line-light {{ $row['duplicate'] ? 'bg-danger-bg' : '' }}">
                    <td class="px-4"><input type="checkbox" wire:click="toggleInclude({{ $i }})" {{ $row['include'] ? 'checked' : '' }}></td>
                    <td class="px-4 text-ink_text-primary">{{ $row['data']['huid_code'] ?? '—' }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $row['data']['category'] ?? '—' }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $row['data']['purity'] ?? '—' }}</td>
                    <td class="px-4 text-ink_text-primary">{{ $row['data']['weight'] ?? '—' }}</td>
                    <td class="px-4">@if($row['duplicate'])<x-ui.badge tone="danger">POSSIBLE DUPLICATE</x-ui.badge>@endif</td>
                </tr>
            @endforeach
        </x-ui.table>
        <div class="flex gap-2.5 mt-[18px]">
            <x-ui.button wire:click="confirmImport" variant="primary">Confirm Import</x-ui.button>
            <x-ui.button wire:click="startOver" variant="secondary">Start Over</x-ui.button>
        </div>
    </x-ui.card>
    @endif

    @if ($step === 4)
    <x-ui.card class="max-w-[480px] text-center !p-8">
        <div class="text-[15px] font-bold text-ink_text-primary mb-1.5">Import complete</div>
        <div class="text-sm text-ink_text-secondary mb-5">{{ $importedCount }} item(s) created.</div>
        <a href="{{ route('stock.items') }}"><x-ui.button variant="primary">View Inventory</x-ui.button></a>
        <x-ui.button wire:click="startOver" variant="secondary" class="ml-2.5">Import Another File</x-ui.button>
    </x-ui.card>
    @endif
</div>
