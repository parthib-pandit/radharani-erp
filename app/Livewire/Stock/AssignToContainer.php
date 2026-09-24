<?php
namespace App\Livewire\Stock;

use App\Models\Stock\Box;
use App\Models\Stock\Item;
use App\Models\Stock\Packet;
use Livewire\Component;
use Livewire\WithFileUploads;

class AssignToContainer extends Component
{
    use WithFileUploads;

    public string $mode = 'scan'; // scan | manual | spreadsheet

    // Scan mode
    public string $scanItemCode = '';
    public string $scanPacketCode = '';
    public ?string $scanResult = null;
    public ?string $scanError = null;

    // Manual mode
    public string $manualSearch = '';
    public ?int $manualItemId = null;
    public ?int $manualPacketId = null;

    // Spreadsheet mode
    public $spreadsheet = null;
    public bool $sheetPreviewed = false;

    public function setMode(string $mode)
    {
        $this->mode = $mode;
        $this->reset(['scanResult', 'scanError', 'sheetPreviewed']);
    }

    public function scanAssign()
    {
        $this->scanResult = null;
        $this->scanError = null;

        $item = Item::where('huid_code', $this->scanItemCode)
            ->orWhere('internal_code', $this->scanItemCode)
            ->first();
        $packet = Packet::where('code', $this->scanPacketCode)->first();

        if (! $item) { $this->scanError = "No item found for code \"{$this->scanItemCode}\"."; return; }
        if (! $packet) { $this->scanError = "No packet found for code \"{$this->scanPacketCode}\"."; return; }

        $item->update(['packet_id' => $packet->id]);
        $label = $item->huid_code ?: $item->internal_code;
        $this->scanResult = "{$label} assigned to packet {$packet->code}.";
        $this->reset(['scanItemCode', 'scanPacketCode']);
    }

    public function manualAssign()
    {
        if (! $this->manualItemId || ! $this->manualPacketId) return;

        Item::whereKey($this->manualItemId)->update(['packet_id' => $this->manualPacketId]);
        session()->flash('message', 'Item assigned to packet.');
        $this->reset(['manualItemId', 'manualPacketId', 'manualSearch']);
    }

    public function previewSheet()
    {
        $this->validate(['spreadsheet' => 'required|file|mimes:csv,txt,xlsx|max:5120']);
        $this->sheetPreviewed = true;
    }

    public function render()
    {
        return view('livewire.stock.assign-to-container', [
            'searchResults' => $this->manualSearch
                ? Item::where('huid_code', 'like', "%{$this->manualSearch}%")
                    ->orWhere('internal_code', 'like', "%{$this->manualSearch}%")
                    ->orWhere('category', 'like', "%{$this->manualSearch}%")
                    ->limit(8)->get()
                : collect(),
            'packets' => Packet::orderBy('code')->get(),
        ])->layout('components.layouts.app', ['title' => 'Assign Items — Radharani Jewellery']);
    }
}
