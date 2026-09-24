<?php
namespace App\Livewire\Admin;

use App\Models\Customer\Customer;
use Livewire\Component;
use Livewire\WithPagination;

// Read-only report for the owner: who referred whom, and whether the
// bonus has actually been earned (first sale confirmed) yet.
class ReferralOverview extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch() { $this->resetPage(); }

    public function render()
    {
        $referrers = Customer::withCount('referrals')
            ->having('referrals_count', '>', 0)
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->with(['referrals' => fn ($q) => $q->withCount('sales')])
            ->orderByDesc('referrals_count')
            ->paginate(15);

        return view('livewire.admin.referral-overview', ['referrers' => $referrers])->layout('components.layouts.app', ['title' => 'Referrals — Radharani Jewellery']);
    }
}
