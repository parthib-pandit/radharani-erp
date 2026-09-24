<?php
namespace App\Livewire\Movement;

use App\Models\Movement\Movement;
use App\Models\Purchase\Vendor;
use App\Models\Stock\Item;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class HallmarkDispatch extends Component
{
    public string $itemSearch = '';
    public ?int $selectedItemId = null;
    public ?int $centreId = null;
    public ?string $expectedReturn = null;
    public ?string $result = null;

    public function submit()
    {
        $this->validate([
            'selectedItemId' => 'required|exists:items,id',
            'centreId' => 'required|exists:vendors,id',
        ]);

        $item = Item::findOrFail($this->selectedItemId);
        $centre = Vendor::findOrFail($this->centreId);

        Movement::create([
            'trackable_type' => 'item',
            'trackable_id' => $item->id,
            'movement_type' => 'hallmark_out',
            'user_id' => Auth::id(),
            'counterparty' => $centre->name,
            'expected_return' => $this->expectedReturn,
            'weight_at_dispatch' => $item->weight,
        ]);
        $item->update(['status' => 'dispatched']);

        $label = $item->huid_code ?: $item->internal_code;
        $this->result = "{$label} sent to {$centre->name} for hallmarking.";
        $this->reset(['selectedItemId', 'itemSearch', 'centreId', 'expectedReturn']);
    }

    public function render()
    {
        return view('livewire.movement.hallmark-dispatch', [
            'itemResults' => $this->itemSearch
                ? Item::where('status', 'in_stock')
                    ->where(fn ($q) => $q->where('huid_code', 'like', "%{$this->itemSearch}%")->orWhere('internal_code', 'like', "%{$this->itemSearch}%"))
                    ->limit(8)->get()
                : collect(),
            'centres' => Vendor::where('type', 'hallmark_center')->orderBy('name')->get(),
        ])->layout('components.layouts.app', ['title' => 'Hallmarking Dispatch — Radharani Jewellery']);
    }
}
