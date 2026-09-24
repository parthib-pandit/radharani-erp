<?php
namespace App\Livewire\Sales;

use App\Models\Notification\PendingNotification;
use App\Models\Sales\Sale;
use App\Models\Stock\Item;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/**
 * Sale Verification Queue — admin-only.
 *
 * FLAG: the real invoice number is still assigned only at creation time
 * (see NewSale's "RESV-" placeholder note) — CLAUDE.md rule 1 forbids
 * updating a sales row's other columns after creation, so verification
 * here does not touch invoice_number. `confirmed_by_accountant` is the
 * one documented exception (SCHEMA_REFERENCE.md / DEVELOPER_GUIDE.md
 * describe it as the confirmation flag the schema was built around),
 * so that flip is the only UPDATE performed on the sales row.
 */
class SaleVerificationQueue extends Component
{
    public function verify(int $saleId)
    {
        $sale = Sale::with('items', 'customer')->findOrFail($saleId);

        if ($sale->confirmed_by_accountant) {
            return;
        }

        $sale->update(['confirmed_by_accountant' => true]);

        $itemIds = $sale->items->pluck('id');
        Item::whereIn('id', $itemIds)->where('status', 'reserved')->update(['status' => 'sold']);

        PendingNotification::create([
            'customer_id' => $sale->customer_id,
            'type' => 'sale_confirmation',
            'recipient_name' => $sale->customer->name ?? null,
            'recipient_phone' => $sale->customer->phone ?? null,
            'message' => "Your purchase (Invoice #{$sale->invoice_number}) for ₹{$sale->total} has been confirmed. Thank you!",
            'status' => 'pending',
            'created_by' => Auth::id(),
        ]);
    }

    public function render()
    {
        return view('livewire.sales.sale-verification-queue', [
            'pending' => Sale::with('customer', 'creator', 'items')
                ->where('confirmed_by_accountant', false)
                ->orderByDesc('id')
                ->get(),
        ])->layout('components.layouts.app', ['title' => 'Sale Verification Queue — Radharani Jewellery']);
    }
}
