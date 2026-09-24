<?php
namespace App\Livewire\Exchange;

use App\Models\Exchange\ExchangeTransaction;
use Livewire\Component;

class StatusTracker extends Component
{
    public function render()
    {
        $transactions = ExchangeTransaction::with('customer')
            ->latest()
            ->get()
            ->map(fn (ExchangeTransaction $t) => [
                'customer' => $t->customer->name ?? '—',
                'weight' => number_format((float) $t->gross_weight, 3) . 'g',
                'stage' => $t->stage,
                'updated' => $t->updated_at->diffForHumans(),
            ]);

        return view('livewire.exchange.status-tracker', ['transactions' => $transactions])->layout('components.layouts.app', ['title' => 'Exchange — Status Tracker — Radharani Jewellery']);
    }
}
