<?php
namespace App\Livewire\Sales;

use App\Models\Sales\Sale;
use Livewire\Component;
use Livewire\WithPagination;

class SalesHistory extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch() { $this->resetPage(); }

    public function render()
    {
        return view('livewire.sales.sales-history', [
            'sales' => Sale::with('customer')
                ->when($this->search, fn ($q) => $q
                    ->where('invoice_number', 'like', "%{$this->search}%")
                    ->orWhereHas('customer', fn ($c) => $c->where('name', 'like', "%{$this->search}%")))
                ->orderByDesc('id')
                ->paginate(20),
        ])->layout('components.layouts.app', ['title' => 'Sales History — Radharani Jewellery']);
    }
}
