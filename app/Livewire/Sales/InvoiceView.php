<?php
namespace App\Livewire\Sales;

use App\Models\Sales\Sale;
use Livewire\Component;

class InvoiceView extends Component
{
    public Sale $sale;

    public function mount(Sale $sale)
    {
        $this->sale = $sale->load('customer', 'items', 'creator');
    }

    public function render()
    {
        return view('livewire.sales.invoice-view')
            ->layout('components.layouts.app', ['title' => "Invoice {$this->sale->invoice_number} — Radharani Jewellery"]);
    }
}
