<?php
namespace App\Livewire\Stock;

use App\Models\Stock\Box;
use App\Models\Stock\Item;
use App\Models\Stock\Packet;
use App\Models\Stock\QrCode;
use App\Services\StockHistoryService;
use Illuminate\Validation\Rule;
use Livewire\Component;

class PacketDetail extends Component
{
    public Packet $packet;

    // Edit modal (code, label, box)
    public bool $showEdit = false;
    public string $code = '';
    public string $label = '';
    public ?int $box_id = null;

    // Add pieces modal
    public bool $showAdd = false;
    public string $itemSearch = '';
    public bool $onlyUnassigned = true;
    public array $addItemIds = [];

    // Move pieces to another packet
    public bool $showMove = false;
    public array $moveItemIds = [];
    public ?int $moveToPacketId = null;

    public function mount(Packet $packet): void
    {
        $this->packet = $packet;
    }

    public function openEdit(): void
    {
        $this->resetValidation();
        $this->code = $this->packet->code;
        $this->label = (string) $this->packet->label;
        $this->box_id = $this->packet->box_id;
        $this->showEdit = true;
    }

    public function saveEdit(): void
    {
        $this->code = strtoupper(trim($this->code));
        $this->box_id = $this->box_id ?: null;
        $data = $this->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('packets', 'code')->ignore($this->packet->id)],
            'label' => ['nullable', 'string', 'max:100'],
            'box_id' => ['nullable', 'exists:boxes,id'],
        ], [], ['code' => 'packet code', 'box_id' => 'box']);

        $this->packet->update($data);
        $this->showEdit = false;
        $this->dispatch('toast', message: 'Packet details saved.', type: 'success');
    }

    public function openAdd(): void
    {
        $this->resetValidation();
        $this->reset(['itemSearch', 'addItemIds']);
        $this->onlyUnassigned = true;
        $this->showAdd = true;
    }

    public function addItems(): void
    {
        $this->validate(['addItemIds' => 'required|array|min:1'], ['addItemIds.required' => 'Pick at least one piece.']);

        $items = Item::whereIn('id', $this->addItemIds)->get();
        foreach ($items as $item) {
            $item->update(['packet_id' => $this->packet->id]); // per-model save so each move is logged
        }

        $this->showAdd = false;
        $this->dispatch('toast', message: "{$items->count()} piece(s) added to {$this->packet->code}.", type: 'success');
    }

    public function removeItem(int $itemId): void
    {
        $item = Item::where('packet_id', $this->packet->id)->findOrFail($itemId);
        $item->update(['packet_id' => null]);
        $this->dispatch('toast', message: "{$item->label} taken out of {$this->packet->code}.", type: 'success');
    }

    public function openMove(?int $itemId = null): void
    {
        $this->resetValidation();
        $this->moveItemIds = $itemId ? [(string) $itemId] : [];
        $this->moveToPacketId = null;
        $this->showMove = true;
    }

    public function moveItems(): void
    {
        $this->validate([
            'moveItemIds' => 'required|array|min:1',
            'moveToPacketId' => ['required', 'exists:packets,id', Rule::notIn([$this->packet->id])],
        ], ['moveItemIds.required' => 'Pick at least one piece to move.'], ['moveToPacketId' => 'destination packet']);

        $target = Packet::findOrFail($this->moveToPacketId);
        $items = Item::where('packet_id', $this->packet->id)->whereIn('id', $this->moveItemIds)->get();
        foreach ($items as $item) {
            $item->update(['packet_id' => $target->id]);
        }

        $this->showMove = false;
        $this->dispatch('toast', message: "{$items->count()} piece(s) moved to {$target->code}.", type: 'success');
    }

    public function issueQr(): void
    {
        QrCode::forTarget('packet', $this->packet->id);
        $this->dispatch('toast', message: 'QR label issued. Print it from the panel.', type: 'success');
    }

    public function render(StockHistoryService $history)
    {
        $this->packet->load('box');
        $items = $this->packet->items()->orderBy('category')->orderBy('id')->get();

        return view('livewire.stock.packet-detail', [
            'items' => $items,
            'statusCounts' => $items->countBy('status'),
            'metalWeights' => $items->groupBy('metal')->map->sum('weight'),
            'events' => $history->forPacket($this->packet),
            'qr' => $this->packet->qrCodes()->latest('id')->first(),
            'boxes' => $this->showEdit ? Box::orderBy('code')->get(['id', 'code', 'label']) : collect(),
            'otherPackets' => $this->showMove
                ? Packet::with('box:id,code')->where('id', '!=', $this->packet->id)->orderBy('code')->get(['id', 'code', 'label', 'box_id'])
                : collect(),
            'candidates' => $this->showAdd
                ? Item::with('packet:id,code')
                    ->where(fn ($q) => $q->whereNull('packet_id')->orWhere('packet_id', '!=', $this->packet->id))
                    ->when($this->onlyUnassigned, fn ($q) => $q->whereNull('packet_id'))
                    ->when($this->itemSearch, fn ($q) => $q->where(fn ($q) => $q
                        ->where('huid_code', 'like', "%{$this->itemSearch}%")
                        ->orWhere('internal_code', 'like', "%{$this->itemSearch}%")
                        ->orWhere('category', 'like', "%{$this->itemSearch}%")
                        ->orWhere('description', 'like', "%{$this->itemSearch}%")))
                    ->whereNotIn('status', ['sold'])
                    ->orderByDesc('id')->limit(60)->get()
                : collect(),
        ])->layout('components.layouts.app', ['title' => "Packet {$this->packet->code} · Radharani Jewellery"]);
    }
}
