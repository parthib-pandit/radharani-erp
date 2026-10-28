<div>
    <x-ui.page-header title="Change Item location" subtitle="Keep the system matching the stock room. Put pieces into packets and packets into boxes."
        :crumbs="[['label' => 'Stock', 'href' => route('stock.items')], ['label' => 'Change Item location']]">
        <x-slot:actions>
            <div class="rj-segment">
                <button type="button" wire:click="setMode('move')" class="{{ $mode === 'move' ? 'is-active' : '' }}"><x-ui.icon name="scan" :size="14" /> Move</button>
                <button type="button" wire:click="setMode('sheet')" class="{{ $mode === 'sheet' ? 'is-active' : '' }}"><x-ui.icon name="file-spreadsheet" :size="14" /> Spreadsheet</button>
            </div>
        </x-slot:actions>
    </x-ui.page-header>

    {{-- ================================================================ MOVE --}}
    @if ($mode === 'move')
        <div class="grid grid-cols-1 xl:grid-cols-[minmax(0,1fr)_420px] gap-6 items-start"
             x-data x-on:scan-ready.window="window.matchMedia('(pointer: fine)').matches && $nextTick(() => $refs.scan && $refs.scan.focus())">
            <div class="space-y-6 min-w-0">
                {{-- 1. what is moving --}}
                <x-ui.card title="What are you moving?" icon="scan" :subtitle="$kind === 'items' ? 'Scan each piece (HUID, internal code or its QR sticker).' : 'Scan each packet (its code or QR sticker).'">
                    <div class="rj-segment mb-4">
                        <button type="button" wire:click="setKind('items')" class="{{ $kind === 'items' ? 'is-active' : '' }}"><x-ui.icon name="gem" :size="14" /> Pieces into a packet</button>
                        <button type="button" wire:click="setKind('packets')" class="{{ $kind === 'packets' ? 'is-active' : '' }}"><x-ui.icon name="package" :size="14" /> Packets into a box</button>
                    </div>

                    <x-ui.scan-button target="#scan-input" submit="form" continuous variant="button"
                        :title="$kind === 'items' ? 'Scan pieces' : 'Scan packets'" label="Scan with camera" class="w-full !h-12 mb-3 sm:hidden" />
                    <form x-on:submit.prevent="const v = $refs.scan.value; $refs.scan.value = ''; if (v.trim()) $wire.scan(v)" class="flex gap-2.5">
                        <div class="relative flex-1 min-w-0">
                            <x-ui.icon name="scan" :size="19" class="absolute left-4 top-1/2 -translate-y-1/2 text-gold pointer-events-none" />
                            <input id="scan-input" x-ref="scan" type="text" autocomplete="off" autofocus placeholder="Scan, or type a code"
                                class="rj-input h-14 pl-12 pr-11 sm:pr-28 text-[15px] sm:text-[17px] font-mono tracking-wider">
                            <span class="hidden sm:inline absolute right-3.5 top-1/2 -translate-y-1/2 text-[11.5px] text-ink_text-muted" wire:loading.remove wire:target="scan">Press Enter</span>
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gold" wire:loading wire:target="scan"><x-ui.icon name="loader" :size="17" class="animate-spin" /></span>
                        </div>
                        <x-ui.scan-button target="#scan-input" submit="form" continuous variant="button" :title="$kind === 'items' ? 'Scan pieces' : 'Scan packets'" label="Camera" class="max-sm:!hidden !h-14 !px-5" />
                    </form>

                    @if ($feedback)
                        <div wire:key="fb-{{ md5(json_encode($feedback) . count($tray)) }}" @class([
                            'mt-3 flex items-center gap-3 px-3.5 py-2.5 rounded-control text-[13px] animate-rise-in',
                            'bg-success-bg text-success' => $feedback['tone'] === 'success',
                            'bg-danger-bg text-danger' => $feedback['tone'] === 'error',
                            'bg-info-bg text-info' => $feedback['tone'] === 'info',
                        ])>
                            <x-ui.icon :name="['success' => 'check-circle', 'error' => 'x-circle', 'info' => 'info'][$feedback['tone']]" :size="16" class="shrink-0" />
                            @if ($feedback['code'] !== '')<span class="rj-code">{{ $feedback['code'] }}</span>@endif
                            <span class="text-ink_text-secondary">{{ $feedback['message'] }}</span>
                        </div>
                    @else
                        <p class="rj-help">A scanner types the code and presses Enter for you. You can also paste several codes at once, separated by spaces or new lines.</p>
                    @endif
                </x-ui.card>

                {{-- 2. where it is going: the same as on a piece's own page --}}
                <x-ui.card :title="$kind === 'items' ? 'Where are they going?' : 'Which box?'" icon="package" subtitle="The move is recorded in each piece's and both packets' history.">
                    <div class="mb-4">
                        <label for="move-scan" class="block text-[12.5px] font-semibold text-ink_text-primary mb-1.5">{{ $kind === 'items' ? 'Scan the packet or box' : 'Scan the box' }}</label>
                        {{-- Not a <form>: Enter here only looks the code up --}}
                        <div class="flex gap-2">
                            <input id="move-scan" x-ref="dscan" type="text" autocomplete="off" placeholder="{{ $kind === 'items' ? 'PKT-1-2 or BOX-01' : 'BOX-01' }}" class="rj-input rj-code flex-1"
                                x-on:keydown.enter.prevent="const v = $refs.dscan.value; $refs.dscan.value = ''; if (v.trim()) $wire.scanDestination(v)">
                            <x-ui.scan-button target="#move-scan" submit="enter" title="{{ $kind === 'items' ? 'Scan the packet or box' : 'Scan the box' }}" variant="button" label="Camera" />
                        </div>
                        @if ($moveScanError)<p class="rj-error"><x-ui.icon name="alert-triangle" :size="12" />{{ $moveScanError }}</p>@endif
                        @if ($moveBox && $kind === 'items')<p class="rj-help">Showing packets in {{ $moveBox }}. <button type="button" class="text-gold-dark font-semibold" wire:click="$set('moveBox', null)">Show all</button></p>@endif
                    </div>
                    <x-ui.field :label="$kind === 'items' ? 'Packet' : 'Box'" for="move-to" error="moveTo">
                        <select id="move-to" wire:model.live="moveTo" class="rj-select @error('moveTo') is-invalid @enderror">
                            <option value="">Choose...</option>
                            <option value="none">{{ $kind === 'items' ? 'Not in a packet' : 'Not in a box' }}</option>
                            @if ($kind === 'items')
                                @foreach ($packetsByBox as $boxCode => $packets)
                                    <optgroup label="{{ $boxCode }}">
                                        @foreach ($packets as $p)<option value="{{ $p->id }}">{{ $p->code }}{{ $p->label ? ' · ' . $p->label : '' }}</option>@endforeach
                                    </optgroup>
                                @endforeach
                            @else
                                @foreach ($boxes as $b)<option value="{{ $b->id }}">{{ $b->code }}{{ $b->label ? ' · ' . $b->label : '' }}</option>@endforeach
                            @endif
                        </select>
                    </x-ui.field>
                </x-ui.card>
            </div>

            {{-- the list --}}
            <div class="space-y-6 xl:sticky xl:top-24">
                <x-ui.card :padding="false" :title="$kind === 'items' ? 'Pieces to move' : 'Packets to move'" icon="list"
                    :subtitle="count($tray) ? count($tray) . ' on the list' . ($trayWeight ? ' · ' . number_format($trayWeight, 3) . ' g' : '') : 'Nothing is moved until you press Move'">
                    @if (count($tray))
                        <x-slot:actions><x-ui.button variant="ghost" size="sm" wire:click="clearList">Clear</x-ui.button></x-slot:actions>
                        <ul class="max-h-[420px] overflow-y-auto divide-y divide-line-light">
                            @foreach ($tray as $i => $t)
                                <li wire:key="tr-{{ $t['type'] }}-{{ $t['id'] }}" class="flex items-center gap-3 px-5 py-3">
                                    <div class="flex-1 min-w-0">
                                        <div class="rj-code text-ink_text-primary truncate">{{ $t['code'] }}</div>
                                        <div class="text-[12.5px] text-ink_text-secondary truncate">{{ $t['detail'] }} · {{ $t['from'] ? 'now in ' . $t['from'] : ($kind === 'items' ? 'not in a packet' : 'not in a box') }}</div>
                                    </div>
                                    <x-ui.button variant="ghost" size="icon-sm" icon="x" wire:click="removeFromList({{ $i }})" aria-label="Take {{ $t['code'] }} off the list" />
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <x-ui.empty-state icon="scan" :title="$kind === 'items' ? 'No pieces yet' : 'No packets yet'" message="Scan them and they appear here. One already on the list is not added again." compact />
                    @endif
                    <div class="px-5 py-4 border-t border-line-light">
                        <x-ui.button class="w-full" size="lg" icon="check" wire:click="move" target="move" :disabled="! count($tray)">Move {{ count($tray) ?: '' }} {{ $kind === 'items' ? \Illuminate\Support\Str::plural('piece', count($tray)) : \Illuminate\Support\Str::plural('packet', count($tray)) }}</x-ui.button>
                    </div>
                </x-ui.card>

                @if ($lastMove)
                    <div class="flex items-center gap-3 p-4 rounded-card border {{ $lastMove['undone'] ? 'bg-surface-sunken border-line' : 'bg-success-bg border-success/20' }} text-[13px]">
                        <x-ui.icon :name="$lastMove['undone'] ? 'repeat' : 'check-circle'" :size="16" class="shrink-0 {{ $lastMove['undone'] ? 'text-ink_text-muted' : 'text-success' }}" />
                        <span class="flex-1">{{ $lastMove['undone'] ? 'Put back where they were.' : 'Last move: ' . $lastMove['count'] . ' ' . ($lastMove['kind'] === 'items' ? \Illuminate\Support\Str::plural('piece', $lastMove['count']) : \Illuminate\Support\Str::plural('packet', $lastMove['count'])) . ' to ' . $lastMove['dest'] . '.' }}</span>
                        @unless ($lastMove['undone'])<button type="button" wire:click="undoLast" class="text-[12px] font-semibold text-ink_text-secondary hover:text-gold-dark shrink-0">Undo</button>@endunless
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- ================================================================ SHEET --}}
    @if ($mode === 'sheet')
        <div class="grid grid-cols-1 xl:grid-cols-[380px_minmax(0,1fr)] gap-6 items-start">
            <x-ui.card title="Upload a spreadsheet" icon="file-spreadsheet" class="xl:sticky xl:top-24">
                <p class="text-[13px] text-ink_text-secondary mb-4">Two columns: the <span class="font-semibold text-ink_text-primary">code</span> of a piece or packet, and the <span class="font-semibold text-ink_text-primary">destination</span> packet or box. CSV or Excel (.xlsx).</p>

                <label class="group relative flex flex-col items-center justify-center gap-2 p-6 rounded-xl border-2 border-dashed border-line hover:border-gold hover:bg-gold-tint/40 cursor-pointer transition-colors text-center"
                       x-data="{ drag: false }" x-on:dragover.prevent="drag = true" x-on:dragleave="drag = false" x-on:drop="drag = false"
                       :class="drag ? 'border-gold bg-gold-tint/60' : ''">
                    <input type="file" wire:model="sheet" accept=".csv,.xlsx,.txt" class="absolute inset-0 opacity-0 cursor-pointer">
                    <span class="w-11 h-11 rounded-xl bg-white ring-1 ring-line shadow-card text-gold-dark flex items-center justify-center">
                        <x-ui.icon name="upload" :size="19" wire:loading.remove wire:target="sheet" />
                        <x-ui.icon name="loader" :size="19" class="animate-spin" wire:loading wire:target="sheet" />
                    </span>
                    <span class="text-[13.5px] font-semibold text-ink_text-primary">{{ $sheetName ?: 'Drop a file here or click to choose' }}</span>
                    <span class="text-[12px] text-ink_text-muted">Up to 1,000 rows, 5 MB</span>
                </label>
                @error('sheet') <p class="rj-error"><x-ui.icon name="alert-triangle" :size="12" />{{ $message }}</p> @enderror

                <div class="flex flex-wrap gap-2 mt-4">
                    <x-ui.button variant="ghost" size="sm" icon="download" wire:click="downloadTemplate">Download template</x-ui.button>
                    @if ($sheetRows)
                        <x-ui.button variant="ghost" size="sm" icon="x" wire:click="resetSheet">Start over</x-ui.button>
                    @endif
                </div>

                @if ($sheetRows)
                    <div class="mt-5 pt-5 border-t border-line-light space-y-2.5 text-[13px]">
                        <div class="flex justify-between"><span class="text-ink_text-secondary">Ready to apply</span><span class="font-bold text-success tabular">{{ $sheetCounts['ok'] }}</span></div>
                        <div class="flex justify-between"><span class="text-ink_text-secondary">Already in place</span><span class="font-semibold tabular">{{ $sheetCounts['same'] }}</span></div>
                        <div class="flex justify-between"><span class="text-ink_text-secondary">Problems</span><span class="font-semibold tabular {{ $sheetCounts['error'] ? 'text-danger' : '' }}">{{ $sheetCounts['error'] }}</span></div>
                        @if ($sheetCounts['done'])
                            <div class="flex justify-between"><span class="text-ink_text-secondary">Applied</span><span class="font-semibold tabular text-gold-dark">{{ $sheetCounts['done'] }}</span></div>
                        @endif
                    </div>
                    <x-ui.button class="w-full mt-5" icon="check" :disabled="! $sheetCounts['ok']"
                        x-on:click="$dispatch('rj-confirm', { title: 'Apply {{ $sheetCounts['ok'] }} row(s)?', message: 'Rows with problems or already in place are skipped. Every move is recorded in the history.', confirm: 'Apply', action: () => $wire.applySheet() })">
                        Apply {{ $sheetCounts['ok'] }} {{ \Illuminate\Support\Str::plural('row', $sheetCounts['ok']) }}
                    </x-ui.button>
                @endif
            </x-ui.card>

            <x-ui.card :padding="false" title="Preview" subtitle="Nothing is changed until you apply" icon="eye">
                @if (! $sheetRows)
                    <x-ui.empty-state icon="file-spreadsheet" title="No file yet" message="Upload a spreadsheet to see, row by row, what will move where." />
                @else
                    <div class="max-h-[640px] overflow-y-auto">
                        <x-ui.table :headers="['Row', 'Code', 'From', 'To', 'Result']">
                            @foreach ($sheetRows as $r)
                                <tr wire:key="sr-{{ $r['line'] }}" @class(['bg-danger-bg/40' => $r['status'] === 'error'])>
                                    <td class="text-ink_text-muted tabular">{{ $r['line'] }}</td>
                                    <td class="rj-code">{{ $r['code'] ?: '(empty)' }}</td>
                                    <td class="rj-code text-[12px] text-ink_text-secondary">{{ $r['from'] ?: '-' }}</td>
                                    <td class="rj-code text-[12px]">{{ $r['dest'] ?: '(empty)' }}</td>
                                    <td>
                                        @switch($r['status'])
                                            @case('ok') <x-ui.badge tone="success" size="sm">Will move</x-ui.badge> @break
                                            @case('done') <x-ui.badge tone="gold" size="sm">Moved</x-ui.badge> @break
                                            @case('same') <x-ui.badge size="sm">Already there</x-ui.badge> @break
                                            @default <x-ui.badge tone="danger" size="sm">{{ $r['message'] }}</x-ui.badge>
                                        @endswitch
                                    </td>
                                </tr>
                            @endforeach
                        </x-ui.table>
                    </div>
                @endif
            </x-ui.card>
        </div>
    @endif
</div>
