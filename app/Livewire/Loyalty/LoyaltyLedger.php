<?php
namespace App\Livewire\Loyalty;

use App\Models\Customer\LoyaltyTransaction;
use Livewire\Component;
use Livewire\WithPagination;

class LoyaltyLedger extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch() { $this->resetPage(); }

    public function render()
    {
        return view('livewire.loyalty.loyalty-ledger', [
            'transactions' => LoyaltyTransaction::with('customer')
                ->when($this->search, fn ($q) => $q->whereHas('customer', fn ($c) => $c->where('name', 'like', "%{$this->search}%")
                    ->orWhere('phone', 'like', "%{$this->search}%")))
                ->orderByDesc('created_at')
                ->paginate(25),
        ])->layout('components.layouts.app', ['title' => 'Loyalty Ledger — Radharani Jewellery ERP']);
    }
}
