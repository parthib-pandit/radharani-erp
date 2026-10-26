<?php
namespace App\Services;

use App\Models\Cash\CashDayClose;
use App\Models\Cash\CashEntry;
use App\Models\Customer\CustomerAdvance;
use App\Models\Orders\OrderPayment;
use App\Models\Sales\SalePayment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * The cash book: two books, Main (the vault) and Petty. The balance of a book is its opening balance plus receipts less
 * payments, and the Main book also carries the cash taken on sales, order advances and customer advances. That cash is
 * read from where it was recorded, never copied, so the book and the sales can not disagree. Every entry is a new row; a
 * mistake is reversed by another row.
 *
 * Not counted yet: the advance an order is placed with (it has no payment mode until the orders screen records one),
 * karigar payments, and exchange cash payouts. Those go in by hand.
 */
class CashBook
{
    public const DENOMINATIONS = [2000, 500, 200, 100, 50, 20, 10, 5, 2, 1];

    public static function today(): string
    {
        return now()->toDateString();
    }

    /** The money in and out written by hand, up to and including a day. */
    public function manualBalance(string $book, string $upTo): float
    {
        return round((float) CashEntry::where('book', $book)->where('occurred_on', '<=', $upTo)
            ->sum(DB::raw("CASE WHEN kind = 'payment' THEN -amount ELSE amount END")), 2);
    }

    /** Cash the Main book picks up on its own, up to and including a day. */
    public function autoBalance(string $book, string $upTo): float
    {
        if ($book !== 'main') {
            return 0.0;
        }

        return round((float) SalePayment::where('mode', 'cash')->whereDate('created_at', '<=', $upTo)->sum('amount')
            + (float) OrderPayment::where('mode', 'cash')->whereDate('created_at', '<=', $upTo)->sum('amount')
            + (float) $this->advances()->whereDate('created_at', '<=', $upTo)->sum('amount'), 2);
    }

    public function balance(string $book, string $upTo): float
    {
        return round($this->manualBalance($book, $upTo) + $this->autoBalance($book, $upTo), 2);
    }

    /** Cash advances paid in by a customer. The rows made when a cancelled sale's money is held (they carry a sale) are not new cash. */
    private function advances()
    {
        return CustomerAdvance::where('kind', 'received')->where('mode', 'cash')->whereNull('sale_id');
    }

    /**
     * One day of a book: where it started, every line with a running balance, and where it ended.
     *
     * @return array{opening: float, in: float, out: float, closing: float, lines: Collection, close: ?CashDayClose}
     */
    public function day(string $book, string $date): array
    {
        $opening = $this->balance($book, \Illuminate\Support\Carbon::parse($date)->subDay()->toDateString());

        $lines = CashEntry::with('user:id,name', 'reversal')->where('book', $book)->where('occurred_on', $date)->orderBy('id')->get()
            ->map(fn (CashEntry $e) => [
                'at' => $e->created_at, 'source' => 'manual', 'id' => $e->id, 'amount' => $e->signed, 'label' => $e->reason, 'note' => $e->note,
                'kind' => $e->kind, 'user' => $e->user?->name, 'reversed' => (bool) $e->reversal, 'reverses' => $e->reverses_id, 'transfer' => (bool) $e->transfer_id, 'url' => null,
            ]);

        if ($book === 'main') {
            $lines = $lines->concat(SalePayment::with('sale.customer:id,name', 'user:id,name')->where('mode', 'cash')->whereDate('created_at', $date)->get()->map(fn ($p) => [
                'at' => $p->created_at, 'source' => 'auto', 'id' => null, 'amount' => (float) $p->amount, 'kind' => 'receipt', 'note' => null, 'reversed' => false, 'reverses' => null, 'transfer' => false,
                'label' => 'Cash on sale #' . $p->sale_id . ($p->sale?->customer ? ' · ' . $p->sale->customer->name : ''), 'user' => $p->user?->name,
                'url' => route('sales.invoice', $p->sale_id),
            ]))->concat(OrderPayment::with('order.customer:id,name', 'user:id,name')->where('mode', 'cash')->whereDate('created_at', $date)->get()->map(fn ($p) => [
                'at' => $p->created_at, 'source' => 'auto', 'id' => null, 'amount' => (float) $p->amount, 'kind' => 'receipt', 'note' => null, 'reversed' => false, 'reverses' => null, 'transfer' => false,
                'label' => 'Cash advance on order #' . $p->order_id . ($p->order?->customer ? ' · ' . $p->order->customer->name : ''), 'user' => $p->user?->name,
                'url' => route('orders.show', $p->order_id),
            ]))->concat($this->advances()->with('customer:id,name', 'user:id,name')->whereDate('created_at', $date)->get()->map(fn ($a) => [
                'at' => $a->created_at, 'source' => 'auto', 'id' => null, 'amount' => (float) $a->amount, 'kind' => 'receipt', 'note' => $a->occasion, 'reversed' => false, 'reverses' => null, 'transfer' => false,
                'label' => 'Cash advance from ' . ($a->customer?->name ?? 'a customer'), 'user' => $a->user?->name, 'url' => null,
            ]));
        }

        $running = $opening;
        $lines = $lines->sortBy(fn ($l) => $l['at']->getTimestamp() . '-' . str_pad((string) ($l['id'] ?? 0), 12, '0', STR_PAD_LEFT))->values()
            ->map(function ($l) use (&$running) {
                $running = round($running + $l['amount'], 2);

                return $l + ['balance' => $running];
            });

        return [
            'opening' => $opening,
            'in' => round($lines->where('amount', '>', 0)->sum('amount'), 2),
            'out' => round(-$lines->where('amount', '<', 0)->sum('amount'), 2),
            'closing' => $running,
            'lines' => $lines,
            'close' => CashDayClose::with('user:id,name')->where('book', $book)->where('closed_on', $date)->latest('id')->first(),
        ];
    }

    /** Money in or out by hand. A payment can not be more than what the book held that day. */
    public function add(string $book, string $kind, float $amount, string $date, string $reason, ?string $note = null, ?string $transferId = null, ?int $reverses = null): CashEntry
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('The amount has to be more than nothing.');
        }
        if ($date > self::today()) {
            throw new \InvalidArgumentException('An entry can not be dated in the future.');
        }
        if ($kind === 'payment' && $amount > $this->balance($book, $date) + 0.005) {
            throw new \InvalidArgumentException('There is only ₹' . number_format($this->balance($book, $date), 2) . ' in this book on that day.');
        }

        return CashEntry::create([
            'book' => $book, 'kind' => $kind, 'amount' => round($amount, 2), 'occurred_on' => $date, 'reason' => trim($reason),
            'note' => $note ? trim($note) : null, 'transfer_id' => $transferId, 'reverses_id' => $reverses, 'user_id' => Auth::id(),
        ]);
    }

    /** Move money from one book to the other: a payment in one and a receipt in the other, tied by one id. */
    public function transfer(string $from, string $to, float $amount, string $date, string $reason): string
    {
        $id = (string) Str::uuid();
        DB::transaction(function () use ($from, $to, $amount, $date, $reason, $id) {
            $this->add($from, 'payment', $amount, $date, $reason, 'Moved to ' . CashEntry::BOOKS[$to], $id);
            $this->add($to, 'receipt', $amount, $date, $reason, 'Moved from ' . CashEntry::BOOKS[$from], $id);
        });

        return $id;
    }

    /** Put an entry right: a new row the other way round that points at it. One entry can be reversed once; the opening balance and moves between books not at all. */
    public function reverse(CashEntry $entry, string $reason): CashEntry
    {
        if ($entry->reversal()->exists() || $entry->reverses_id) {
            throw new \RuntimeException('This entry has already been put right, or is itself a correction.');
        }
        if ($entry->kind === 'opening' || $entry->transfer_id) {
            throw new \RuntimeException($entry->kind === 'opening' ? 'The opening balance can not be reversed.' : 'A move between the books can not be reversed here. Move the money back instead.');
        }

        if ($entry->kind === 'receipt' && $entry->amount > $this->balance($entry->book, self::today()) + 0.005) {
            throw new \RuntimeException('Putting this right takes out ₹' . number_format($entry->amount, 2) . ', but the book holds only ₹' . number_format($this->balance($entry->book, self::today()), 2) . ' today.');
        }

        return CashEntry::create([
            'book' => $entry->book, 'kind' => $entry->kind === 'receipt' ? 'payment' : 'receipt', 'amount' => $entry->amount, 'occurred_on' => self::today(), // the correction is made today, whenever the entry was
            'reason' => 'Correction: ' . trim($reason), 'note' => 'Reverses entry #' . $entry->id . ' (' . $entry->reason . ')', 'reverses_id' => $entry->id, 'user_id' => Auth::id(),
        ]);
    }

    /** Count the notes and coins of a day and keep the difference from what the book says. */
    public function close(string $book, string $date, array $counts, ?string $note = null): CashDayClose
    {
        if ($date > self::today()) {
            throw new \InvalidArgumentException('A day that has not come can not be closed.');
        }
        $denominations = [];
        foreach (self::DENOMINATIONS as $d) {
            $n = (int) ($counts[$d] ?? 0);
            if ($n < 0) {
                throw new \InvalidArgumentException('A count can not be negative.');
            }
            $denominations[(string) $d] = $n;
        }
        $counted = round(collect($denominations)->sum(fn ($n, $d) => $n * (int) $d), 2);
        $expected = $this->balance($book, $date);

        return CashDayClose::create([
            'book' => $book, 'closed_on' => $date, 'expected' => $expected, 'counted' => $counted, 'difference' => round($counted - $expected, 2),
            'denominations' => $denominations, 'note' => $note ? trim($note) : null, 'user_id' => Auth::id(),
        ]);
    }
}
