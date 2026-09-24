<?php
namespace App\Livewire\Pricing;

use App\Models\Movement\RateLog;
use Livewire\Component;
use Livewire\WithPagination;

class RateHistoryLog extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.pricing.rate-history-log', [
            'rates' => RateLog::with('updater')->latest('created_at')->paginate(30),
        ])->layout('components.layouts.app', ['title' => 'Rate History — Radharani Jewellery']);
    }
}
