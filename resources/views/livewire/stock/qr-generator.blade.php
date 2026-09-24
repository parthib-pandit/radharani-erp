<div>
    <x-ui.page-header title="QR Code Generator" subtitle="Generate a code for immediate printing, or a batch ahead of time." />

    <div class="flex gap-1 bg-surface-muted rounded-control p-1 mb-5 max-w-[480px]">
        <button wire:click="$set('mode','single')" class="flex-1 h-9 border-0 rounded-lg text-xs font-bold cursor-pointer {{ $mode==='single' ? 'bg-white shadow-card text-ink_text-primary' : 'bg-transparent text-ink_text-secondary' }}">Single — print now</button>
        <button wire:click="$set('mode','batch')" class="flex-1 h-9 border-0 rounded-lg text-xs font-bold cursor-pointer {{ $mode==='batch' ? 'bg-white shadow-card text-ink_text-primary' : 'bg-transparent text-ink_text-secondary' }}">Batch — admin</button>
    </div>

    @if ($mode === 'single')
    <x-ui.card class="max-w-[480px]">
        <div class="font-semibold text-[13.5px] text-ink_text-primary mb-3.5">Generate one code now</div>
        @if ($singleError)
            <div class="bg-danger-bg text-danger rounded-control px-3.5 py-2.5 mb-4 text-sm">{{ $singleError }}</div>
        @endif
        <form wire:submit="generateSingle">
            <label class="block text-[11.5px] text-ink_text-secondary mb-1">Target type</label>
            <select wire:model="singleTargetType" class="rj-select w-full mb-3.5">
                <option value="item">Item</option>
                <option value="packet">Packet</option>
                <option value="box">Box</option>
            </select>
            <label class="block text-[11.5px] text-ink_text-secondary mb-1">Code / scan</label>
            <input type="text" wire:model="singleTargetCode" placeholder="HUID / internal / packet / box code" class="rj-input w-full mb-4.5">
            <x-ui.button type="submit" variant="primary" class="w-full">Generate &amp; Print</x-ui.button>
        </form>

        @if ($singleResult)
        <div class="mt-5 pt-4.5 border-t border-line-light text-center">
            <div class="w-[120px] h-[120px] bg-white border border-line rounded-lg mx-auto mb-2.5 flex items-center justify-center bg-[repeating-linear-gradient(45deg,#211D19_0,#211D19_4px,#fff_4px,#fff_8px)] bg-[length:16px_16px] bg-blend-multiply opacity-85"></div>
            <div class="font-bold text-[13px] text-ink_text-primary">{{ $singleResult['label'] }}</div>
            <div class="text-[11.5px] text-ink_text-secondary font-mono">{{ $singleResult['code'] }}</div>
            <x-ui.button variant="secondary" class="mt-3">Send to Printer</x-ui.button>
        </div>
        @endif
    </x-ui.card>
    @endif

    @if ($mode === 'batch')
    <x-ui.card class="max-w-[560px]">
        <div class="font-semibold text-[13.5px] text-ink_text-primary mb-3.5">Generate a batch ahead of time</div>
        <div class="grid grid-cols-3 gap-3 mb-4.5">
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Target type</label>
                <select wire:model="batchTargetType" class="rj-select w-full">
                    <option value="item">Items</option>
                    <option value="packet">Packets</option>
                    <option value="box">Boxes</option>
                </select>
            </div>
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Status filter</label>
                <select wire:model="batchStatusFilter" class="rj-select w-full" {{ $batchTargetType !== 'item' ? 'disabled' : '' }}>
                    <option value="in_stock">In stock only</option>
                    <option value="">All</option>
                </select>
            </div>
            <div>
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">How many</label>
                <input type="number" wire:model="batchCount" min="1" max="100" class="rj-input w-full">
            </div>
        </div>
        <x-ui.button wire:click="generateBatch" variant="primary">Generate Batch</x-ui.button>

        @if (!empty($batchResults))
        <div class="mt-5 pt-4 border-t border-line-light">
            <div class="flex justify-between items-center mb-2.5">
                <div class="text-[12.5px] font-bold text-ink_text-primary">{{ count($batchResults) }} codes generated</div>
                <x-ui.button variant="secondary">Download / Print All</x-ui.button>
            </div>
            <div class="grid grid-cols-3 gap-2.5">
                @foreach ($batchResults as $r)
                <div class="border border-line rounded-lg p-2.5 text-center">
                    <div class="w-14 h-14 mx-auto mb-1.5 border border-line rounded bg-[repeating-linear-gradient(45deg,#211D19_0,#211D19_3px,#fff_3px,#fff_6px)] bg-[length:12px_12px]"></div>
                    <div class="text-[11px] font-bold text-ink_text-primary">{{ $r['label'] }}</div>
                    <div class="text-[10px] text-ink_text-secondary font-mono">{{ $r['code'] }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </x-ui.card>
    @endif
</div>
