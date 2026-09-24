<?php
namespace App\Livewire\Movement;

use App\Models\Movement\Movement;
use App\Models\Stock\Item;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PendingReviewQueue extends Component
{
    // #9: admin is exclusively responsible for closing pending_review
    // items into normal stock. Gated on movement.approve since this route
    // isn't otherwise permission-scoped in routes/movements.php.
    public function confirmClose(int $itemId)
    {
        abort_unless(Auth::user()?->can('movement.approve'), 403);

        $item = Item::findOrFail($itemId);

        if ($item->status !== 'pending_review') {
            session()->flash('message', 'That item is no longer pending review.');
            return;
        }

        // Never update/delete the movement row itself except to mark it
        // approved — the movement stays exactly as recorded, only
        // approved_by changes, which is the correct use of that column
        // for "admin approving/reviewing the movement" (non-negotiable
        // rule 1: movements are insert-only otherwise).
        $lastMovement = $item->movements()->first();
        if ($lastMovement) {
            $lastMovement->update(['approved_by' => Auth::id()]);
        }

        $item->update(['status' => 'in_stock']);

        session()->flash('message', 'Item confirmed into stock.');
    }

    public function render()
    {
        // #9: items that HAVE returned and are awaiting admin
        // confirmation — not items still out (that was the previous,
        // wrong, query here).
        $pendingItems = Item::where('status', 'pending_review')->get()->map(function ($item) {
            return ['item' => $item, 'movement' => $item->movements()->first()];
        });

        return view('livewire.movement.pending-review-queue', [
            'pendingItems' => $pendingItems,
        ])->layout('components.layouts.app', ['title' => 'Pending Review — Admin Review — Radharani Jewellery']);
    }
}
