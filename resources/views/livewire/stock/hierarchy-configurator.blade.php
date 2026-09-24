<div>
    <x-ui.page-header title="Box → Packet → Item Configurator" subtitle="Build or reorganize the whole hierarchy in one workspace." />

    @if (session('message'))
        <div class="bg-success-bg text-success rounded-control px-3.5 py-2.5 mb-5 text-sm">{{ session('message') }}</div>
    @endif

    <div class="grid grid-cols-2 gap-3.5 mb-6">
        <x-ui.card>
            <div class="font-semibold text-sm text-ink_text-primary mb-2.5">New Box</div>
            <form wire:submit="createBox" class="flex gap-2">
                <input type="text" wire:model="newBoxCode" placeholder="Code" class="rj-input flex-1">
                <input type="text" wire:model="newBoxLabel" placeholder="Label" class="rj-input flex-1">
                <x-ui.button type="submit" variant="secondary">Add</x-ui.button>
            </form>
            @error('newBoxCode') <div class="text-danger text-[11px] mt-1">{{ $message }}</div> @enderror
        </x-ui.card>
        <x-ui.card>
            <div class="font-semibold text-sm text-ink_text-primary mb-2.5">New Packet</div>
            <form wire:submit="createPacket" class="flex gap-2">
                <select wire:model="newPacketBoxId" class="rj-select flex-1">
                    <option value="">In box...</option>
                    @foreach ($boxes as $b)<option value="{{ $b->id }}">{{ $b->code }}</option>@endforeach
                </select>
                <input type="text" wire:model="newPacketCode" placeholder="Code" class="rj-input flex-1">
                <x-ui.button type="submit" variant="secondary">Add</x-ui.button>
            </form>
            @error('newPacketBoxId') <div class="text-danger text-[11px] mt-1">{{ $message }}</div> @enderror
        </x-ui.card>
    </div>

    <x-ui.card class="!p-0 overflow-hidden">
        @foreach ($boxes as $box)
        <div class="border-b border-line-light">
            <div wire:click="toggleBox({{ $box->id }})" class="px-[18px] py-3.5 flex justify-between items-center cursor-pointer">
                <div>
                    <span class="font-bold text-[13.5px] text-ink_text-primary">{{ $box->code }}</span>
                    <span class="text-ink_text-secondary text-[12.5px]"> — {{ $box->label }} · {{ $box->packets->count() }} packet(s)</span>
                </div>
                <span class="text-ink_text-secondary">{{ $expandedBoxId === $box->id ? '▾' : '▸' }}</span>
            </div>
            @if ($expandedBoxId === $box->id)
            <div class="pb-4 pl-8 pr-[18px]">
                @forelse ($box->packets as $packet)
                <div class="py-2.5 border-t border-line-light">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold text-[12.5px] text-ink_text-primary">{{ $packet->code }} — {{ $packet->label }}</span>
                        <select wire:change="movePacket({{ $packet->id }}, $event.target.value || null)" class="rj-select text-[11.5px]">
                            <option value="">Move to box...</option>
                            @foreach ($boxes as $b) <option value="{{ $b->id }}" {{ $b->id === $box->id ? 'selected' : '' }}>{{ $b->code }}</option> @endforeach
                        </select>
                    </div>
                    @foreach ($packet->items as $item)
                        <div class="flex justify-between py-1.5 pl-4 text-xs text-ink_text-primary">
                            <span>{{ $item->huid_code ?: $item->internal_code }} — {{ $item->category }}, {{ $item->weight }}g</span>
                            <a href="{{ route('stock.items.show', $item) }}" class="text-gold font-bold">View</a>
                        </div>
                    @endforeach
                </div>
                @empty
                    <div class="text-[12.5px] text-ink_text-secondary pt-2.5">No packets in this box.</div>
                @endforelse
            </div>
            @endif
        </div>
        @endforeach
    </x-ui.card>

    @if ($unboxedPackets->isNotEmpty())
    <x-ui.card class="mt-5">
        <div class="font-semibold text-sm text-ink_text-primary mb-2.5">Unassigned Packets</div>
        @foreach ($unboxedPackets as $packet)
            <div class="flex justify-between items-center py-1.5 text-[12.5px] text-ink_text-primary">
                <span>{{ $packet->code }} — {{ $packet->label }}</span>
                <select wire:change="movePacket({{ $packet->id }}, $event.target.value || null)" class="rj-select">
                    <option value="">Assign to box...</option>
                    @foreach ($boxes as $b) <option value="{{ $b->id }}">{{ $b->code }}</option> @endforeach
                </select>
            </div>
        @endforeach
    </x-ui.card>
    @endif

    @if ($unpacketedItems->isNotEmpty())
    <x-ui.card class="mt-3.5">
        <div class="font-semibold text-sm text-ink_text-primary mb-2.5">Unassigned Items (showing up to 30)</div>
        @foreach ($unpacketedItems as $item)
            <div class="flex justify-between items-center py-1.5 text-[12.5px] text-ink_text-primary">
                <span>{{ $item->huid_code ?: $item->internal_code }} — {{ $item->category }}, {{ $item->weight }}g</span>
                <select wire:change="moveItem({{ $item->id }}, $event.target.value || null)" class="rj-select">
                    <option value="">Assign to packet...</option>
                    @foreach ($allPackets as $p) <option value="{{ $p->id }}">{{ $p->code }}</option> @endforeach
                </select>
            </div>
        @endforeach
    </x-ui.card>
    @endif
</div>
