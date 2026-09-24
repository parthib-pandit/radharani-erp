<?php
namespace App\Livewire\Exchange;

use App\Models\Exchange\ExchangeTransaction;
use Livewire\Component;

/**
 * Settles a final valuation against a real `exchange_transactions` row.
 * Only rows in stage 'tested' (purity readings done, deduction computed)
 * are ready to be valued — staff pick one of those instead of just
 * searching by customer, since a customer can have multiple outstanding
 * exchanges. Settling moves the row through 'valued' then 'settled' in
 * one action (judgment call: the doc doesn't require a separate
 * "mark valued" step before "settle", so both stage transitions happen
 * together at submit time), and stamps settled_by/settled_at.
 */
class AccountsValuation extends Component
{
    public string $customerSearch = '';
    public ?int $customerId = null;
    public ?int $transactionId = null;
    public float $finalValue = 0;
    public ?string $result = null;

    public function selectTransaction(int $transactionId)
    {
        $this->transactionId = $transactionId;
        $this->result = null;
    }

    public function settle()
    {
        $this->validate([
            'transactionId' => 'required|exists:exchange_transactions,id',
            'finalValue' => 'required|numeric|min:0.01',
        ]);

        $transaction = ExchangeTransaction::where('stage', 'tested')->findOrFail($this->transactionId);

        $transaction->update([
            'final_value' => $this->finalValue,
            'stage' => 'settled',
            'settled_by' => auth()->id(),
            'settled_at' => now(),
        ]);

        $this->result = "Recorded ₹" . number_format($this->finalValue, 2) . " against {$transaction->customer->name}'s exchange (#{$transaction->id}).";

        $this->reset(['transactionId', 'finalValue', 'customerId', 'customerSearch']);
    }

    public function render()
    {
        $readyTransactions = ExchangeTransaction::with('customer')
            ->where('stage', 'tested')
            ->when($this->customerSearch, function ($query) {
                $query->whereHas('customer', function ($q) {
                    $q->where('name', 'like', "%{$this->customerSearch}%")
                        ->orWhere('phone', 'like', "%{$this->customerSearch}%");
                });
            })
            ->latest()
            ->get();

        return view('livewire.exchange.accounts-valuation', [
            'readyTransactions' => $readyTransactions,
        ])->layout('components.layouts.app', ['title' => 'Exchange — Final Valuation — Radharani Jewellery']);
    }
}
