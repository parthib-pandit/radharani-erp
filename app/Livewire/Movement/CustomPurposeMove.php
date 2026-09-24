<?php
namespace App\Livewire\Movement;

use App\Models\Movement\Movement;
use App\Models\Stock\Item;
use App\Services\PhotoCompressionService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class CustomPurposeMove extends Component
{
    use WithFileUploads;

    public string $direction = 'out'; // out | in
    public string $itemSearch = '';
    public ?int $selectedItemId = null;
    public string $purposeLabel = '';
    public $photo = null;
    public ?string $result = null;

    public function setDirection(string $direction)
    {
        $this->direction = $direction;
        $this->reset(['result', 'selectedItemId', 'itemSearch', 'purposeLabel', 'photo']);
    }

    public function submit()
    {
        $this->validate([
            'selectedItemId' => 'required|exists:items,id',
            'purposeLabel' => 'required|string|max:50',
            'photo' => 'nullable|image|max:5120',
        ]);

        $item = Item::findOrFail($this->selectedItemId);

        // Photos always go through PhotoCompressionService — never saved
        // directly (CLAUDE.md rule 4).
        $photoPath = $this->photo
            ? app(PhotoCompressionService::class)->store($this->photo, 'movements/photo')
            : null;

        Movement::create([
            'trackable_type' => 'item',
            'trackable_id' => $item->id,
            'movement_type' => $this->direction === 'out' ? 'photo_out' : 'photo_in',
            'purpose_label' => $this->purposeLabel,
            'user_id' => Auth::id(),
            'photo_path' => $photoPath,
        ]);

        if ($this->direction === 'out') {
            $item->update(['status' => 'dispatched']);
        } else {
            $item->update(['status' => 'in_stock']);
        }

        $label = $item->huid_code ?: $item->internal_code;
        $this->result = $label . ' ' . ($this->direction === 'out' ? 'dispatched for ' . $this->purposeLabel . '.' : 'returned.');
        $this->reset(['selectedItemId', 'itemSearch', 'purposeLabel', 'photo']);
    }

    public function render()
    {
        return view('livewire.movement.custom-purpose-move', [
            'itemResults' => $this->itemSearch
                ? Item::where('status', $this->direction === 'out' ? 'in_stock' : 'dispatched')
                    ->where(fn ($q) => $q->where('huid_code', 'like', "%{$this->itemSearch}%")->orWhere('internal_code', 'like', "%{$this->itemSearch}%"))
                    ->limit(8)->get()
                : collect(),
        ])->layout('components.layouts.app', ['title' => 'Photography / Custom Purpose — Radharani Jewellery']);
    }
}
