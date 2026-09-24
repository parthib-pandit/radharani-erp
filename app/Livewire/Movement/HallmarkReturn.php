<?php
namespace App\Livewire\Movement;

use App\Models\Movement\Movement;
use App\Models\Stock\Item;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class HallmarkReturn extends Component
{
    public ?int $selectedItemId = null;
    public string $huidCode = '';
    public bool $generateInternal = false;
    // #8: recorded by name (free text) — the tagger may be an external
    // hallmarking-centre person, not necessarily a system user, so this is
    // no longer constrained to users.id.
    public string $taggedByName = '';
    public float $weightLoss = 0;
    public ?string $result = null;

    public function selectItem(int $id)
    {
        $this->selectedItemId = $id;
        $this->reset(['result']);
    }

    public function confirmReturn()
    {
        $this->validate([
            'selectedItemId' => 'required|exists:items,id',
            'taggedByName' => 'required|string|max:100',
            'weightLoss' => 'required|numeric|min:0',
        ]);

        if (! $this->generateInternal && ! $this->huidCode) {
            $this->addError('huidCode', 'Enter the HUID, or tick "generate internal code" if none was given.');
            return;
        }

        $item = Item::findOrFail($this->selectedItemId);

        if ($this->generateInternal) {
            $item->internal_code = Item::generateInternalCode();
        } else {
            $item->huid_code = $this->huidCode;
        }

        // #9: sits pending_review until admin confirms — never straight
        // back to in_stock from here.
        $item->status = 'pending_review';
        $item->save();

        Movement::create([
            'trackable_type' => 'item',
            'trackable_id' => $item->id,
            'movement_type' => 'hallmark_in',
            'user_id' => Auth::id(),
            // #6: manually-entered loss, real column now.
            'weight_loss' => $this->weightLoss,
            // #8: free-text name, real column — approved_by is left alone,
            // it means "admin who approved/reviewed", not "who tagged it".
            'tagged_by' => $this->taggedByName,
            'actual_return' => now()->toDateString(),
        ]);

        $label = $item->huid_code ?: $item->internal_code;
        $this->result = "{$label} returned from hallmarking — awaiting admin review before it's back in stock.";
        $this->reset(['selectedItemId', 'huidCode', 'generateInternal', 'taggedByName', 'weightLoss']);
    }

    public function render()
    {
        // Item::movements() returns a plain query, not an Eloquent relation,
        // so this filters in PHP rather than via whereHas().
        $openDispatches = Item::where('status', 'dispatched')->get()->filter(function ($item) {
            $last = $item->movements()->whereIn('movement_type', ['hallmark_out', 'hallmark_in'])->first();
            return $last && $last->movement_type === 'hallmark_out';
        });

        return view('livewire.movement.hallmark-return', [
            'openDispatches' => $openDispatches,
        ])->layout('components.layouts.app', ['title' => 'Hallmarking Return — Radharani Jewellery']);
    }
}
