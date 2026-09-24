<?php
namespace App\Livewire\Stock;

use App\Models\Stock\Box;
use App\Models\Stock\Item;
use App\Models\Stock\Packet;
use Livewire\Component;

class HierarchyConfigurator extends Component
{
    public ?int $expandedBoxId = null;

    public string $newBoxCode = '';
    public string $newBoxLabel = '';

    public ?int $newPacketBoxId = null;
    public string $newPacketCode = '';
    public string $newPacketLabel = '';

    public function toggleBox(int $boxId)
    {
        $this->expandedBoxId = $this->expandedBoxId === $boxId ? null : $boxId;
    }

    public function createBox()
    {
        $this->validate(['newBoxCode' => 'required|string|max:30|unique:boxes,code']);
        Box::create(['code' => $this->newBoxCode, 'label' => $this->newBoxLabel]);
        $this->reset(['newBoxCode', 'newBoxLabel']);
        session()->flash('message', 'Box created.');
    }

    public function createPacket()
    {
        $this->validate([
            'newPacketBoxId' => 'required|exists:boxes,id',
            'newPacketCode' => 'required|string|max:30|unique:packets,code',
        ]);
        Packet::create(['box_id' => $this->newPacketBoxId, 'code' => $this->newPacketCode, 'label' => $this->newPacketLabel]);
        $this->reset(['newPacketBoxId', 'newPacketCode', 'newPacketLabel']);
        session()->flash('message', 'Packet created and nested.');
    }

    public function movePacket(int $packetId, ?int $boxId)
    {
        Packet::whereKey($packetId)->update(['box_id' => $boxId]);
        session()->flash('message', 'Packet reassigned.');
    }

    public function moveItem(int $itemId, ?int $packetId)
    {
        Item::whereKey($itemId)->update(['packet_id' => $packetId]);
        session()->flash('message', 'Item reassigned.');
    }

    public function render()
    {
        return view('livewire.stock.hierarchy-configurator', [
            'boxes' => Box::with('packets.items')->orderBy('code')->get(),
            'unboxedPackets' => Packet::whereNull('box_id')->orderBy('code')->get(),
            'unpacketedItems' => Item::whereNull('packet_id')->orderBy('id')->limit(30)->get(),
            'allPackets' => Packet::orderBy('code')->get(),
        ])->layout('components.layouts.app', ['title' => 'Hierarchy Configurator — Radharani Jewellery']);
    }
}
