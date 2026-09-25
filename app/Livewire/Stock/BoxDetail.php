<?php
namespace App\Livewire\Stock;

use App\Models\Stock\Box;
use App\Models\Stock\Item;
use App\Models\Stock\Packet;
use App\Models\Stock\QrCode;
use App\Services\StockHistoryService;
use App\Support\StockCodes;
use Illuminate\Validation\Rule;
use Livewire\Component;

class BoxDetail extends Component
{
    public Box $box;

    // Edit modal
    public bool $showEdit = false;
    public string $code = '';
    public string $label = '';

    // Add packets modal: move existing packets in, or create a new one here
    public bool $showAdd = false;
    public string $addMode = 'existing'; // existing | new
    public string $packetSearch = '';
    public array $addPacketIds = [];
    public string $newPacketCode = '';
    public string $newPacketLabel = '';

    public function mount(Box $box): void
    {
        $this->box = $box;
    }

    public function openEdit(): void
    {
        $this->resetValidation();
        $this->code = $this->box->code;
        $this->label = (string) $this->box->label;
        $this->showEdit = true;
    }

    public function saveEdit(): void
    {
        $this->code = strtoupper(trim($this->code));
        $data = $this->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('boxes', 'code')->ignore($this->box->id)],
            'label' => ['nullable', 'string', 'max:100'],
        ], [], ['code' => 'box code']);

        $this->box->update($data);
        $this->showEdit = false;
        $this->dispatch('toast', message: 'Box details saved.', type: 'success');
    }

    public function openAdd(): void
    {
        $this->resetValidation();
        $this->reset(['addPacketIds', 'packetSearch', 'newPacketLabel']);
        $this->addMode = 'existing';
        $this->newPacketCode = StockCodes::next(Packet::class, "PKT-{$this->box->id}-");
        $this->showAdd = true;
    }

    public function addPackets(): void
    {
        if ($this->addMode === 'new') {
            $this->newPacketCode = strtoupper(trim($this->newPacketCode));
            $this->validate([
                'newPacketCode' => ['required', 'string', 'max:50', 'unique:packets,code'],
                'newPacketLabel' => ['nullable', 'string', 'max:100'],
            ], [], ['newPacketCode' => 'packet code']);

            Packet::create(['box_id' => $this->box->id, 'code' => $this->newPacketCode, 'label' => $this->newPacketLabel]);
            $message = "Packet {$this->newPacketCode} created in {$this->box->code}.";
        } else {
            $this->validate(['addPacketIds' => 'required|array|min:1'], ['addPacketIds.required' => 'Pick at least one packet to move in.']);

            $packets = Packet::whereIn('id', $this->addPacketIds)->where(fn ($q) => $q->whereNull('box_id')->orWhere('box_id', '!=', $this->box->id))->get();
            foreach ($packets as $packet) {
                $packet->update(['box_id' => $this->box->id]); // per-model save so the move is logged
            }
            $message = "{$packets->count()} packet(s) moved into {$this->box->code}.";
        }

        $this->showAdd = false;
        $this->dispatch('toast', message: $message, type: 'success');
    }

    public function removePacket(int $packetId): void
    {
        $packet = Packet::where('box_id', $this->box->id)->findOrFail($packetId);
        $packet->update(['box_id' => null]);
        $this->dispatch('toast', message: "Packet {$packet->code} taken out of {$this->box->code}.", type: 'success');
    }

    public function issueQr(): void
    {
        QrCode::forTarget('box', $this->box->id);
        $this->dispatch('toast', message: 'QR label issued. Print it from the panel.', type: 'success');
    }

    public function render(StockHistoryService $history)
    {
        $packets = $this->box->packets()
            ->withCount('items')
            ->withSum('items', 'weight')
            ->orderBy('code')
            ->get();

        $items = Item::whereIn('packet_id', $packets->pluck('id'));

        return view('livewire.stock.box-detail', [
            'packets' => $packets,
            'pieces' => (clone $items)->count(),
            'weight' => (float) (clone $items)->sum('weight'),
            'statusCounts' => (clone $items)->selectRaw('status, count(*) as c')->groupBy('status')->pluck('c', 'status'),
            'metalWeights' => (clone $items)->selectRaw('metal, sum(weight) as w')->groupBy('metal')->pluck('w', 'metal'),
            'events' => $history->forBox($this->box),
            'qr' => $this->box->qrCodes()->latest('id')->first(),
            'candidatePackets' => $this->showAdd && $this->addMode === 'existing'
                ? Packet::with('box:id,code')->withCount('items')
                    ->where(fn ($q) => $q->whereNull('box_id')->orWhere('box_id', '!=', $this->box->id))
                    ->when($this->packetSearch, fn ($q) => $q->where(fn ($q) => $q
                        ->where('code', 'like', "%{$this->packetSearch}%")->orWhere('label', 'like', "%{$this->packetSearch}%")))
                    ->orderByRaw('box_id is not null')->orderBy('code')->limit(50)->get()
                : collect(),
        ])->layout('components.layouts.app', ['title' => "Box {$this->box->code} · Radharani Jewellery"]);
    }
}
