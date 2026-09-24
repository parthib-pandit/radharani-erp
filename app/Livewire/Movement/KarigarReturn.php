<?php
namespace App\Livewire\Movement;

use App\Models\Movement\KarigarRawBatch;
use App\Models\Movement\Movement;
use App\Models\Stock\Item;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class KarigarReturn extends Component
{
    public ?int $selectedItemId = null;
    public float $weightLoss = 0;
    public ?string $actualReturn = null;
    public string $note = '';
    public bool $needsHallmarking = false;

    public bool $isRawMaterialReturn = false;
    public ?int $selectedBatchId = null;
    public string $newCategory = '';
    public string $newPurity = '';
    public float $newWeight = 0;
    public string $newDescription = '';

    public ?string $result = null;

    public function selectItem(int $id)
    {
        $this->selectedItemId = $id;
        $this->reset(['result']);
    }

    public function confirmReturn()
    {
        if ($this->isRawMaterialReturn) {
            $this->confirmRawMaterialReturn();
            return;
        }

        $this->validate([
            'selectedItemId' => 'required|exists:items,id',
            'weightLoss' => 'required|numeric|min:0',
            'actualReturn' => 'nullable|date',
        ]);

        $item = Item::findOrFail($this->selectedItemId);

        Movement::create([
            'trackable_type' => 'item',
            'trackable_id' => $item->id,
            'movement_type' => 'karigar_in',
            'user_id' => Auth::id(),
            'actual_return' => $this->actualReturn ?: now()->toDateString(),
            // #6: weight loss is manually entered, never derived — real
            // column now, not smuggled into the free-text note.
            'weight_loss' => $this->weightLoss,
            'note' => $this->note ?: null,
        ]);

        $label = $item->huid_code ?: $item->internal_code;

        if ($this->needsHallmarking) {
            Movement::create([
                'trackable_type' => 'item',
                'trackable_id' => $item->id,
                'movement_type' => 'hallmark_out',
                'user_id' => Auth::id(),
                'purpose_label' => 'Chained from karigar return',
            ]);
            // #7: still effectively "out" — chained straight into hallmarking.
            $item->update(['status' => 'dispatched']);
            $this->result = "{$label} returned and chained straight into Hallmarking Dispatch.";
        } else {
            // #9: returned items sit in pending_review until admin
            // confirms — never go straight back to in_stock from here.
            $item->update(['status' => 'pending_review']);
            $this->result = "{$label} returned — awaiting admin review before it's back in stock.";
        }

        $this->reset(['selectedItemId', 'weightLoss', 'actualReturn', 'note', 'needsHallmarking']);
    }

    // #5 sub-flow (c): the finished piece has no identity until it comes
    // back — created here as a brand-new Item, linked to the raw batch it
    // came from, and held pending_review like any other return.
    //
    // NOTE — single piece per return only. The requirements doc itself
    // flags "does one raw-material dispatch always split cleanly" as still
    // open with the client, so this deliberately doesn't build a
    // multi-line splitter yet. Extend to a repeatable $lines array (see
    // NewPurchaseEntry) once that's resolved.
    protected function confirmRawMaterialReturn()
    {
        $this->validate([
            'selectedBatchId' => 'required|exists:karigar_raw_batches,id',
            'newCategory' => 'required|string|max:50',
            'newPurity' => 'required|string|max:10',
            'newWeight' => 'required|numeric|min:0.001',
            'newDescription' => 'nullable|string|max:100',
            'actualReturn' => 'nullable|date',
        ]);

        $batch = KarigarRawBatch::findOrFail($this->selectedBatchId);

        $item = Item::create([
            'metal' => $batch->metal,
            'internal_code' => Item::generateInternalCode(),
            'category' => $this->newCategory,
            'purity' => $this->newPurity,
            'weight' => $this->newWeight,
            'description' => $this->newDescription ?: null,
            // Making charge isn't known at return time — placeholder,
            // set for real once admin confirms the item out of review.
            'making_type' => 'flat_per_piece',
            'making_value' => 0,
            'source_karigar_batch_id' => $batch->id,
            'status' => 'pending_review',
        ]);

        $batch->update([
            'status' => 'returned',
            'actual_return' => $this->actualReturn ?: now()->toDateString(),
        ]);

        $this->result = "New item {$item->internal_code} created from raw-material return and awaiting admin review.";
        $this->reset([
            'isRawMaterialReturn', 'selectedBatchId', 'newCategory', 'newPurity',
            'newWeight', 'newDescription', 'actualReturn',
        ]);
    }

    public function render()
    {
        // Item::movements() returns a plain query, not an Eloquent relation,
        // so this filters in PHP rather than via whereHas().
        $openDispatches = Item::where('status', 'dispatched')->get()->filter(function ($item) {
            $last = $item->movements()->whereIn('movement_type', ['karigar_out', 'karigar_in'])->first();
            return $last && $last->movement_type === 'karigar_out';
        });

        return view('livewire.movement.karigar-return', [
            'openDispatches' => $openDispatches,
            'openRawBatches' => KarigarRawBatch::whereIn('status', ['dispatched', 'partially_returned'])
                ->orderByDesc('created_at')->get(),
        ])->layout('components.layouts.app', ['title' => 'Karigar Return — Radharani Jewellery']);
    }
}
