<?php
namespace App\Livewire\Purchase;

use App\Models\Purchase\Vendor;
use Livewire\Component;
use Livewire\WithPagination;

class VendorManager extends Component
{
    use WithPagination;

    public string $search = '';
    public string $typeFilter = 'all';
    public ?int $editingId = null;

    public string $name = '';
    public string $type = 'karigar';
    public string $phone = '';
    public string $address = '';
    public string $balance = '0';

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'type' => 'required|in:karigar,supplier,hallmark_center',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'balance' => 'required|numeric',
        ];
    }

    public function updatingSearch() { $this->resetPage(); }
    public function updatingTypeFilter() { $this->resetPage(); }

    public function edit(int $id)
    {
        $v = Vendor::findOrFail($id);
        $this->editingId = $v->id;
        $this->name = $v->name;
        $this->type = $v->type;
        $this->phone = (string) $v->phone;
        $this->address = (string) $v->address;
        $this->balance = (string) $v->balance;
    }

    public function save()
    {
        $this->validate();

        Vendor::updateOrCreate(['id' => $this->editingId], [
            'name' => $this->name,
            'type' => $this->type,
            'phone' => $this->phone,
            'address' => $this->address,
            'balance' => $this->balance,
        ]);

        $this->cancel();
        session()->flash('message', 'Vendor saved.');
    }

    public function cancel()
    {
        $this->reset(['editingId', 'name', 'phone', 'address']);
        $this->type = 'karigar';
        $this->balance = '0';
    }

    public function render()
    {
        return view('livewire.purchase.vendor-manager', [
            'vendors' => Vendor::query()
                ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('phone', 'like', "%{$this->search}%"))
                ->when($this->typeFilter !== 'all', fn ($q) => $q->where('type', $this->typeFilter))
                ->orderBy('name')
                ->paginate(15),
        ])->layout('components.layouts.app', ['title' => 'Vendors — Radharani Jewellery']);
    }
}
