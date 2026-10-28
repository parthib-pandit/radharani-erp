<?php
namespace App\Support;

use App\Models\Customer\Customer;
use App\Models\Customer\CustomerAdvance;
use App\Models\Notification\PendingNotification;
use App\Models\Orders\Order;
use App\Models\Orders\OrderPayment;
use App\Models\Sales\Sale;
use App\Models\Sales\SalePayment;
use Illuminate\Support\Facades\Auth;

/**
 * Money events as records in the Messages queue (28 Oct feedback). These are written for the employees, not the customer:
 * who entered what, for whom, and how much. They carry no customer phone and are marked "seen" rather than sent. The
 * customer's own sale confirmation is a different message and is not touched.
 */
class MoneyMessages
{
    public const TYPES = ['money_sale' => 'Sale entered', 'money_payment' => 'Payment received', 'money_advance' => 'Advance received', 'money_cancelled' => 'Sale cancelled'];

    private static function inr($n): string
    {
        return '₹' . number_format(abs((float) $n), 2);
    }

    private static function who(): string
    {
        return Auth::user()?->name ?? 'Someone';
    }

    private static function put(string $type, ?Customer $customer, string $message, string $relatedType, int $relatedId): PendingNotification
    {
        return PendingNotification::create([
            'customer_id' => $customer?->id, 'type' => $type, 'recipient_name' => 'Staff', 'recipient_phone' => null,
            'message' => $message, 'status' => 'pending', 'related_type' => $relatedType, 'related_id' => $relatedId, 'created_by' => Auth::id(),
        ]);
    }

    /** A sale was saved: for whom, the total, and what was paid with it. */
    public static function saleEntered(Sale $sale): PendingNotification
    {
        $sale->loadMissing('customer', 'payments');
        $paid = round((float) $sale->payments->sum('amount'), 2);
        $parts = $sale->payments->groupBy('mode')->map(fn ($rows, $mode) => (SalePayment::MODES[$mode] ?? $mode) . ' ' . self::inr($rows->sum('amount')))->implode(', ');

        return self::put('money_sale', $sale->customer, sprintf('Sale #%d entered by %s for %s: %s. %s Balance %s. Waiting for an admin to verify it.',
            $sale->id, self::who(), $sale->customer?->name ?? 'a customer', self::inr($sale->total),
            $paid > 0 ? 'Paid ' . self::inr($paid) . ' (' . $parts . ').' : 'Nothing paid yet.', self::inr($sale->total - $paid)), 'sale', $sale->id);
    }

    /** More money came in on a bill that was already saved. */
    public static function paymentReceived(Sale $sale, SalePayment $payment): PendingNotification
    {
        $sale->loadMissing('customer');

        return self::put('money_payment', $sale->customer, sprintf('%s received by %s on sale #%d for %s, entered by %s. Balance now %s.',
            self::inr($payment->amount), SalePayment::MODES[$payment->mode] ?? $payment->mode, $sale->id, $sale->customer?->name ?? 'a customer', self::who(), self::inr($sale->fresh()->balance)), 'sale', $sale->id);
    }

    /** Money paid in on the customer's account with no sale behind it. */
    public static function advanceReceived(CustomerAdvance $advance): PendingNotification
    {
        $advance->loadMissing('customer');

        return self::put('money_advance', $advance->customer, sprintf('%s advance received by %s from %s, entered by %s. It is held on the account%s.',
            self::inr($advance->amount), CustomerAdvance::MODES[$advance->mode] ?? 'cash', $advance->customer?->name ?? 'a customer', self::who(), $advance->occasion ? " ({$advance->occasion})" : ''), 'customer', (int) $advance->customer_id);
    }

    /** Money paid in on a custom order. */
    public static function orderAdvance(Order $order, float $amount, string $mode): PendingNotification
    {
        $order->loadMissing('customer');

        return self::put('money_advance', $order->customer, sprintf('%s advance received by %s on order #%d for %s, entered by %s.',
            self::inr($amount), OrderPayment::MODES[$mode] ?? $mode, $order->id, $order->customer?->name ?? 'a customer', self::who()), 'order', $order->id);
    }

    /** A sale was cancelled: why, and where the money went. */
    public static function saleCancelled(Sale $sale, string $reason, float $held, float $returned): PendingNotification
    {
        $sale->loadMissing('customer');

        return self::put('money_cancelled', $sale->customer, sprintf('Sale #%d for %s (%s) cancelled by %s%s: %s.%s%s',
            $sale->id, $sale->customer?->name ?? 'a customer', self::inr($sale->total), self::who(), $sale->confirmed_by_accountant ? ' after it was verified' : '', $reason,
            $held > 0 ? ' ' . self::inr($held) . ' that was paid is held on the account as an advance.' : '',
            $returned > 0 ? ' ' . self::inr($returned) . ' of credit and advance went back to the customer.' : ''), 'sale', $sale->id);
    }
}
