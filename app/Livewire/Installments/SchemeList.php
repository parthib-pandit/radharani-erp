<?php
namespace App\Livewire\Installments;

use App\Models\Customer\InstallmentScheme;
use Livewire\Component;
use Livewire\WithPagination;

class SchemeList extends Component
{
    use WithPagination;

    public string $statusFilter = 'all';

    public function updatingStatusFilter() { $this->resetPage(); }

    public function markStatus(int $id, string $status)
    {
        InstallmentScheme::whereKey($id)->update(['status' => $status]);
    }

    public function render()
    {
        return view('livewire.installments.scheme-list', [
            'schemes' => InstallmentScheme::with('customer')
                ->when($this->statusFilter !== 'all', fn ($q) => $q->where('status', $this->statusFilter))
                ->orderByDesc('id')
                ->paginate(20),
        ])->layout('components.layouts.app', ['title' => 'Installment Schemes — Radharani Jewellery ERP']);
    }
}
