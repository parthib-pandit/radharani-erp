<?php
namespace App\Livewire\Reports;

use App\Models\Sales\Sale;
use App\Models\Stock\Item;
use Livewire\Component;

/**
 * Owner Dashboard — stock location breakdown, phone-readable.
 *
 * "Current location" isn't a stored column — it's derived from each item's
 * currentMovement() (a real helper already on Item, doc-commented there as
 * "used by owner dashboard"). Walking every dispatched item's latest
 * movement is an N+1 query, acceptable at this shop's scale (hundreds, not
 * tens of thousands, of items) — flagging it as a spot to revisit if the
 * dataset grows.
 */
class OwnerDashboard extends Component
{
    public function render()
    {
        $counts = Item::selectRaw('status, count(*) as qty, sum(weight) as weight')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $dispatchedLocations = Item::where('status', 'dispatched')->get()
            ->groupBy(function ($item) {
                $m = $item->currentMovement();
                return $m ? str($m->movement_type)->before('_out')->toString() : 'unknown';
            })
            ->map(fn ($items) => ['qty' => $items->count(), 'weight' => $items->sum('weight')]);

        return view('livewire.reports.owner-dashboard', [
            'inStock' => $counts->get('in_stock'),
            'dispatched' => $counts->get('dispatched'),
            'sold' => $counts->get('sold'),
            'dispatchedLocations' => $dispatchedLocations,
            'todaySales' => Sale::whereDate('created_at', today())->sum('total'),
            'todaySalesCount' => Sale::whereDate('created_at', today())->count(),
        ])->layout('components.layouts.app', ['title' => 'Owner Dashboard — Radharani Jewellery ERP']);
    }
}
