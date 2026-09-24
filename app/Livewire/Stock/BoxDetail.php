<?php
namespace App\Livewire\Stock;

use App\Models\Stock\Box;
use Livewire\Component;

class BoxDetail extends Component
{
    public Box $box;

    public function mount(Box $box)
    {
        $this->box = $box->load('packets.items');
    }

    public function render()
    {
        return view('livewire.stock.box-detail', [
            'movements' => $this->box->movements()->with('user')->orderByDesc('created_at')->get(),
        ])->layout('components.layouts.app', ['title' => "Box {$this->box->code} — Radharani Jewellery"]);
    }
}
