<?php
namespace App\Livewire\Concerns;

use App\Models\Sales\Sale;
use App\Services\SaleCanceller;

/**
 * The cancel-a-sale action for a Livewire page: opens the reason dialog, then hands over to SaleCanceller.
 * The page shows <x-sales.cancel-modal /> and calls startCancel(id) from a button.
 */
trait CancelsSales
{
    public bool $showCancel = false;
    public ?int $cancelSaleId = null;
    public string $cancelReason = '';

    public function startCancel(int $saleId): void
    {
        $sale = Sale::findOrFail($saleId);
        abort_unless(SaleCanceller::allowed($sale), 403);
        $this->cancelSaleId = $sale->id;
        $this->cancelReason = '';
        $this->resetValidation();
        $this->showCancel = true;
    }

    public function confirmCancel()
    {
        $sale = Sale::findOrFail($this->cancelSaleId);
        abort_unless(SaleCanceller::allowed($sale), 403);
        $this->validate(['cancelReason' => ['required', 'string', 'max:255']], ['cancelReason.required' => 'Say why the sale is cancelled.'], ['cancelReason' => 'reason']);

        app(SaleCanceller::class)->cancel($sale, $this->cancelReason);

        $this->showCancel = false;
        $this->reset(['cancelSaleId', 'cancelReason']);

        return $this->afterCancel($sale);
    }

    /** What the page does next: reload its own figures by default. */
    protected function afterCancel(Sale $sale)
    {
        $this->dispatch('toast', message: "Sale #{$sale->id} is cancelled. The pieces are back in stock.", type: 'success');
    }
}
