<?php
namespace App\Livewire\Portal;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CustomerDashboard extends Component
{
    public string $tab = 'purchases';

    public function setTab(string $tab)
    {
        $this->tab = $tab;
    }

    public function logout()
    {
        Auth::guard('customer')->logout();
        session()->invalidate();
        session()->regenerateToken();
        $this->redirect(route('portal.login'), navigate: true);
    }

    public function render()
    {
        $customer = Auth::guard('customer')->user()->load([
            'sales' => fn ($q) => $q->orderByDesc('created_at'),
            'sales.items',
            'loyaltyTransactions' => fn ($q) => $q->orderByDesc('created_at'),
            'installmentSchemes.payments',
            'referrals' => fn ($q) => $q->withCount('sales'),
        ]);

        return view('livewire.portal.customer-dashboard', ['customer' => $customer]);
    }
}
