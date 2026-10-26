<?php
namespace App\Services;

use App\Models\Customer\CustomerAdvance;
use App\Models\Exchange\ExchangeApplication;
use App\Models\Orders\Order;
use App\Models\Notification\PendingNotification;
use App\Models\Sales\Sale;
use App\Models\Sales\SaleCancellation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Cancels a sale. The sale row is never touched (rule 1): the cancellation is a row of its own, and everything the sale
 * did is undone by new rows. The pieces go back into stock (each one on its own, so its history shows it). Exchange credit
 * and advance the sale had used go back to the customer. Money paid in cash, UPI, card, bank or cheque is held on the
 * customer's account as an advance, so nothing disappears and it can be paid back or used on a later sale.
 */
class SaleCanceller
{
    /** Who may cancel: a verified sale only an admin (sale.approve), one waiting for verification anyone who can make a sale. */
    public static function allowed(Sale $sale): bool
    {
        $user = Auth::user();
        if (! $user || $sale->isCancelled()) {
            return false;
        }

        return $sale->confirmed_by_accountant ? $user->can('sale.approve') : $user->can('sale.create');
    }

    public function cancel(Sale $sale, string $reason): SaleCancellation
    {
        $reason = trim($reason);
        if ($reason === '') {
            throw new \InvalidArgumentException('Say why the sale is cancelled.');
        }

        return DB::transaction(function () use ($sale, $reason) {
            $sale = Sale::lockForUpdate()->with('items', 'payments')->findOrFail($sale->id);
            if ($sale->cancellation()->exists()) {
                throw new \RuntimeException('This sale is already cancelled.');
            }

            $held = 0.0;
            $returned = 0.0;
            foreach ($sale->payments->groupBy('mode') as $mode => $rows) {
                $paid = round((float) $rows->sum('amount'), 2);
                if ($paid <= 0) {
                    continue;
                }
                if ($mode === 'exchange') {
                    foreach ($rows->groupBy('exchange_transaction_id') as $exchangeId => $parts) {
                        $amount = round((float) $parts->sum('amount'), 2);
                        ExchangeApplication::create([
                            'exchange_transaction_id' => $exchangeId, 'kind' => 'sale', 'applied_id' => $sale->id,
                            'amount' => -$amount, 'note' => "Sale #{$sale->id} cancelled, credit returned", 'user_id' => Auth::id(),
                        ]);
                        $returned += $amount;
                    }
                } elseif ($mode === 'advance') {
                    CustomerAdvance::create([
                        'customer_id' => $sale->customer_id, 'kind' => 'received', 'amount' => $paid, 'sale_id' => $sale->id,
                        'note' => "Advance used on sale #{$sale->id}, returned: sale cancelled", 'user_id' => Auth::id(),
                    ]);
                    $returned += $paid;
                } else {
                    CustomerAdvance::create([
                        'customer_id' => $sale->customer_id, 'kind' => 'received', 'amount' => $paid, 'mode' => $mode, 'sale_id' => $sale->id,
                        'occasion' => 'Sale cancelled', 'note' => "Paid on sale #{$sale->id}, held on the account: sale cancelled", 'user_id' => Auth::id(),
                    ]);
                    $held += $paid;
                }
            }

            // Back into stock, one piece at a time so each piece's own timeline shows reserved/sold -> in stock.
            $sale->items->whereIn('status', ['reserved', 'sold'])->each(fn ($item) => $item->update(['status' => 'in_stock']));

            // An order this sale was delivering is open again.
            Order::where('converted_sale_id', $sale->id)->get()->each(function (Order $order) {
                $order->update(['converted_sale_id' => null, 'status' => $order->status === 'delivered' ? 'ready' : $order->status]);
            });

            // A confirmation message nobody has sent yet is no longer true.
            PendingNotification::where('related_type', 'sale')->where('related_id', $sale->id)->where('status', 'pending')->delete();

            $cancellation = SaleCancellation::create([
                'sale_id' => $sale->id, 'reason' => $reason, 'was_verified' => (bool) $sale->confirmed_by_accountant,
                'held_on_account' => round($held, 2), 'credit_returned' => round($returned, 2), 'user_id' => Auth::id(),
            ]);

            activity('sales')->performedOn($sale)->causedBy(Auth::user())->event('cancelled')
                ->log("Sale #{$sale->id} cancelled" . ($sale->confirmed_by_accountant ? ' after verification' : '') . ": {$reason}");

            return $cancellation;
        });
    }
}
