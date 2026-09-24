<?php
namespace App\Livewire\Stock;

use App\Models\Stock\Item;
use Livewire\Component;

class ItemDetail extends Component
{
    public Item $item;

    public function mount(Item $item)
    {
        $this->item = $item->load('packet.box');
    }

    public function render()
    {
        $itemLabel = $this->item->huid_code ?: $this->item->internal_code;

        return view('livewire.stock.item-detail', [
            'movements' => $this->item->movements()->with('user')->get(),
            'pair' => $this->item->pair_group_id ? $this->item->pairedWith()->first() : null,
        ])->layout('components.layouts.app', ['title' => "Item {$itemLabel} — Radharani Jewellery"]);
    }
}
