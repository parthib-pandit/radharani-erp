<?php
namespace App\Livewire\Admin;

use App\Models\Customer\Customer;
use Livewire\Component;

/**
 * Customer Detail (staff-facing) — combined view: purchase history,
 * exchange balance, loyalty points, instalment scheme status.
 *
 * "Current orders" is spec'd but there is no custom_orders table anywhere
 * in the schema (flagged already back in Section 5 — Custom Orders has no
 * backing table at all), so that block below is frontend-only and flagged
 * rather than invented.
 */
class CustomerDetail extends Component
{
    public Customer $customer;
    public string $tab = 'purchases';

    public function mount(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function setTab(string $tab)
    {
        $this->tab = $tab;
    }

    public function render()
    {
        return view('livewire.admin.customer-detail', [
            'sales' => $this->customer->sales()->with('items')->orderByDesc('id')->get(),
            'loyaltyTransactions' => $this->customer->loyaltyTransactions()->orderByDesc('created_at')->get(),
            'installmentSchemes' => $this->customer->installmentSchemes()->with('payments')->get(),
        ])->layout('components.layouts.app', ['title' => $this->customer->name.' — Radharani Jewellery']);
    }
}
