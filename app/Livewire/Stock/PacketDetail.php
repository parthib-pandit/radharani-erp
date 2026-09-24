<?php
namespace App\Livewire\Stock;

use App\Models\Stock\Packet;
use Livewire\Component;

class PacketDetail extends Component
{
    public Packet $packet;

    public function mount(Packet $packet)
    {
        $this->packet = $packet->load('box', 'items');
    }

    public function render()
    {
        return view('livewire.stock.packet-detail', [
            'movements' => $this->packet->movements()->with('user')->orderByDesc('created_at')->get(),
        ])->layout('components.layouts.app', ['title' => "Packet {$this->packet->code} — Radharani Jewellery"]);
    }
}
