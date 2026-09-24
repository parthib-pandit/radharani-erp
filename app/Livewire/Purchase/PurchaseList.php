<?php
namespace App\Livewire\Purchase;

use App\Models\Purchase\Purchase;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Purchase List / Payment Status — read view.
 *
 * CLAUDE.md rule 1 forbids ever updating a purchases row once written.
 * payment_status therefore cannot be changed here after entry (that would
 * be an UPDATE to a purchases row) — same conflict already flagged and
 * worked around the same way in Sales' Verification Queue. A real status
 * change needs a correction/new-row mechanism approved by an owner, which
 * doesn't exist in the schema yet, so the status shown here is exactly
 * what was recorded at New Purchase Entry and cannot be edited from this
 * screen.
 */
class PurchaseList extends Component
{
    use WithPagination;

    public string $statusFilter = 'all';
    public string $search = '';

    public function updatingStatusFilter() { $this->resetPage(); }
    public function updatingSearch() { $this->resetPage(); }

    public function render()
    {
        return view('livewire.purchase.purchase-list', [
            'purchases' => Purchase::with('vendor')
                ->when($this->statusFilter !== 'all', fn ($q) => $q->where('payment_status', $this->statusFilter))
                ->when($this->search, fn ($q) => $q->where('invoice_number', 'like', "%{$this->search}%")
                    ->orWhereHas('vendor', fn ($v) => $v->where('name', 'like', "%{$this->search}%")))
                ->orderByDesc('id')
                ->paginate(20),
        ])->layout('components.layouts.app', ['title' => 'Purchases — Radharani Jewellery']);
    }
}
