<?php
namespace App\Livewire\Cash;

use App\Models\Cash\CashDayClose;
use App\Models\Cash\CashEntry;
use App\Services\CashBook;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * Cash Book (25 Oct feedback): the Main cash (vault) and Petty cash books. A day at a time: where it started, every line
 * with a running balance, where it ended, and a day close that counts the money by denomination. Cash taken on sales and
 * advances shows in the Main book on its own. Nothing is edited: a mistake is put right with a new row.
 */
class CashBookPage extends Component
{
    #[Url(except: 'main')]
    public string $book = 'main';

    #[Url(except: '')]
    public string $date = '';

    // Money in or out
    public bool $showEntry = false;
    public string $entryKind = 'receipt';
    public $entryAmount = '';
    public string $entryReason = '';
    public string $entryNote = '';

    // Between the books
    public bool $showTransfer = false;
    public $transferAmount = '';
    public string $transferReason = '';

    // Opening balance
    public bool $showOpening = false;
    public $openingAmount = '';

    // Putting an entry right
    public bool $showReverse = false;
    public ?int $reverseId = null;
    public string $reverseReason = '';

    // Closing the day
    public bool $showClose = false;
    public array $counts = [];
    public string $closeNote = '';

    public function mount(): void
    {
        abort_unless(Auth::user()?->can('cashbook.manage'), 403);
        $this->book = array_key_exists($this->book, CashEntry::BOOKS) ? $this->book : 'main';
        $this->date = $this->validDate($this->date);
    }

    private function guard(): void
    {
        abort_unless(Auth::user()?->can('cashbook.manage'), 403);
    }

    private function validDate(string $date): string
    {
        try {
            $d = Carbon::createFromFormat('Y-m-d', $date);
        } catch (\Throwable) {
            return CashBook::today();
        }

        return $d && $d->format('Y-m-d') === $date && $date <= CashBook::today() ? $date : CashBook::today();
    }

    public function updatedDate(): void
    {
        $this->date = $this->validDate($this->date);
    }

    public function setBook(string $book): void
    {
        $this->book = array_key_exists($book, CashEntry::BOOKS) ? $book : 'main';
    }

    public function shiftDay(int $days): void
    {
        $this->date = $this->validDate(Carbon::parse($this->date)->addDays($days)->toDateString());
    }

    public function goToday(): void
    {
        $this->date = CashBook::today();
    }

    private function other(): string
    {
        return $this->book === 'main' ? 'petty' : 'main';
    }

    // ---------------------------------------------------------------- money in or out

    public function startEntry(string $kind): void
    {
        $this->guard();
        $this->resetValidation();
        $this->reset(['entryAmount', 'entryReason', 'entryNote']);
        $this->entryKind = $kind === 'payment' ? 'payment' : 'receipt';
        $this->showEntry = true;
    }

    public function saveEntry(): void
    {
        $this->guard();
        $this->validate([
            'entryAmount' => ['required', 'numeric', 'min:0.01', 'max:99999999'],
            'entryReason' => ['required', 'string', 'min:2', 'max:255'],
            'entryNote' => ['nullable', 'string', 'max:255'],
        ], [], ['entryAmount' => 'amount', 'entryReason' => 'reason']);

        try {
            app(CashBook::class)->add($this->book, $this->entryKind, (float) $this->entryAmount, $this->date, $this->entryReason, $this->entryNote ?: null);
        } catch (\InvalidArgumentException $e) {
            $this->addError('entryAmount', $e->getMessage());

            return;
        }

        $this->showEntry = false;
        $this->dispatch('toast', message: ($this->entryKind === 'receipt' ? 'Receipt' : 'Payment') . ' recorded.', type: 'success');
    }

    // ---------------------------------------------------------------- between the books

    public function startTransfer(): void
    {
        $this->guard();
        $this->resetValidation();
        $this->reset(['transferAmount', 'transferReason']);
        $this->showTransfer = true;
    }

    public function saveTransfer(): void
    {
        $this->guard();
        $this->validate([
            'transferAmount' => ['required', 'numeric', 'min:0.01', 'max:99999999'],
            'transferReason' => ['required', 'string', 'min:2', 'max:255'],
        ], [], ['transferAmount' => 'amount', 'transferReason' => 'reason']);

        try {
            app(CashBook::class)->transfer($this->book, $this->other(), (float) $this->transferAmount, $this->date, $this->transferReason);
        } catch (\InvalidArgumentException $e) {
            $this->addError('transferAmount', $e->getMessage());

            return;
        }

        $this->showTransfer = false;
        $this->dispatch('toast', message: '₹' . number_format((float) $this->transferAmount, 2) . ' moved to ' . CashEntry::BOOKS[$this->other()] . '.', type: 'success');
    }

    // ---------------------------------------------------------------- opening balance

    public function startOpening(): void
    {
        $this->guard();
        $this->resetValidation();
        $this->reset(['openingAmount']);
        $this->showOpening = true;
    }

    public function saveOpening(): void
    {
        $this->guard();
        $this->validate(['openingAmount' => ['required', 'numeric', 'min:0.01', 'max:99999999']], [], ['openingAmount' => 'amount']);
        if (CashEntry::where('book', $this->book)->where('kind', 'opening')->exists()) {
            $this->addError('openingAmount', 'This book already has an opening balance.');

            return;
        }

        CashEntry::create(['book' => $this->book, 'kind' => 'opening', 'amount' => round((float) $this->openingAmount, 2), 'occurred_on' => $this->date,
            'reason' => 'Opening balance', 'user_id' => Auth::id()]);

        $this->showOpening = false;
        $this->dispatch('toast', message: 'Opening balance set.', type: 'success');
    }

    // ---------------------------------------------------------------- put an entry right

    public function startReverse(int $id): void
    {
        $this->guard();
        $this->resetValidation();
        $this->reverseId = CashEntry::where('book', $this->book)->findOrFail($id)->id;
        $this->reverseReason = '';
        $this->showReverse = true;
    }

    public function saveReverse(): void
    {
        $this->guard();
        $this->validate(['reverseReason' => ['required', 'string', 'min:2', 'max:200']], [], ['reverseReason' => 'reason']);

        try {
            app(CashBook::class)->reverse(CashEntry::where('book', $this->book)->findOrFail($this->reverseId), $this->reverseReason);
        } catch (\RuntimeException $e) {
            $this->addError('reverseReason', $e->getMessage());

            return;
        }

        $this->showReverse = false;
        $this->dispatch('toast', message: 'Put right with a new entry. The old one stays on record.', type: 'success');
    }

    // ---------------------------------------------------------------- close the day

    public function startClose(): void
    {
        $this->guard();
        $this->resetValidation();
        $this->counts = array_fill_keys(CashBook::DENOMINATIONS, '');
        $this->closeNote = '';
        $this->showClose = true;
    }

    public function getCountedProperty(): float
    {
        return round(collect(CashBook::DENOMINATIONS)->sum(fn ($d) => max(0, (int) ($this->counts[$d] ?? 0)) * $d), 2);
    }

    public function saveClose(): void
    {
        $this->guard();
        $this->validate([
            'counts.*' => ['nullable', 'integer', 'min:0', 'max:1000000'],
            'closeNote' => ['nullable', 'string', 'max:255'],
        ], [], ['counts.*' => 'count']);

        $close = app(CashBook::class)->close($this->book, $this->date, $this->counts, $this->closeNote ?: null);
        $this->showClose = false;
        $this->dispatch('toast', message: abs($close->difference) < 0.005
            ? 'Day closed. The count matches the book.'
            : 'Day closed with a difference of ' . ($close->difference > 0 ? '+' : '-') . '₹' . number_format(abs($close->difference), 2) . '.', type: abs($close->difference) < 0.005 ? 'success' : 'warning');
    }

    public function render()
    {
        $this->guard();
        $cash = app(CashBook::class);
        $today = CashBook::today();

        return view('livewire.cash.cash-book', [
            'books' => CashEntry::BOOKS,
            'day' => $cash->day($this->book, $this->date),
            'balances' => ['main' => $cash->balance('main', $today), 'petty' => $cash->balance('petty', $today)],
            'hasOpening' => CashEntry::where('book', $this->book)->where('kind', 'opening')->exists(),
            'denominations' => CashBook::DENOMINATIONS,
            'recentCloses' => CashDayClose::with('user:id,name')->where('book', $this->book)->latest('closed_on')->latest('id')->limit(10)->get(),
            'isToday' => $this->date === $today,
            'otherBook' => CashEntry::BOOKS[$this->other()],
        ])->layout('components.layouts.app', ['title' => 'Cash Book · Radharani Jewellery']);
    }
}
