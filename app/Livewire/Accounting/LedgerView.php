<?php
namespace App\Livewire\Accounting;

use App\Models\Accounting\Account;
use App\Models\Accounting\Transaction;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Ledger / Transactions View — read-only, gated by ledger.view.
 *
 * transactions rows are auto-generated (reference_type sale/purchase/
 * installment/manual) elsewhere in the app; this screen has no write
 * action at all, matching the spec (view-only ledger).
 */
class LedgerView extends Component
{
    use WithPagination;

    public string $accountFilter = 'all';
    public string $typeFilter = 'all';

    public function updatingAccountFilter() { $this->resetPage(); }
    public function updatingTypeFilter() { $this->resetPage(); }

    public function render()
    {
        return view('livewire.accounting.ledger-view', [
            'accounts' => Account::orderBy('name')->get(),
            'transactions' => Transaction::with(['account', 'creator'])
                ->when($this->accountFilter !== 'all', fn ($q) => $q->where('account_id', $this->accountFilter))
                ->when($this->typeFilter !== 'all', fn ($q) => $q->where('reference_type', $this->typeFilter))
                ->orderByDesc('created_at')
                ->paginate(30),
        ])->layout('components.layouts.app', ['title' => 'Ledger — Radharani Jewellery ERP']);
    }
}
