<?php
namespace App\Livewire\Movement;

use App\Models\Customer\Customer;
use App\Models\Customer\CustomerMaterialJob;
use App\Models\Movement\KarigarRawBatch;
use App\Models\Movement\Movement;
use App\Models\Purchase\Vendor;
use App\Models\Stock\Item;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class KarigarDispatch extends Component
{
    // 'tagged' | 'customer_material' | 'raw_material'
    public string $situation = 'tagged';

    public string $itemSearch = '';
    public ?int $selectedItemId = null;

    public string $customerSearch = '';
    public ?int $selectedCustomerId = null;

    public ?int $vendorId = null;
    public float $weight = 0;
    public string $metalType = 'gold';
    public ?string $expectedReturn = null;
    public string $note = '';

    // customer_material only — what the material actually is (schema requires it).
    public string $description = '';

    // raw_material only — optional extra context on the batch.
    public string $purity = '';
    public string $purposeLabel = '';

    public ?string $result = null;

    public function setSituation(string $situation)
    {
        $this->situation = $situation;
        $this->reset(['result']);
    }

    public function dispatchTagged()
    {
        $this->validate([
            'selectedItemId' => 'required|exists:items,id',
            'vendorId' => 'required|exists:vendors,id',
            'expectedReturn' => 'nullable|date',
        ]);

        $item = Item::findOrFail($this->selectedItemId);
        $vendor = Vendor::findOrFail($this->vendorId);

        Movement::create([
            'trackable_type' => 'item',
            'trackable_id' => $item->id,
            'movement_type' => 'karigar_out',
            'purpose_label' => 'Repair',
            'user_id' => Auth::id(),
            'counterparty' => $vendor->name,
            'expected_return' => $this->expectedReturn,
            'weight_at_dispatch' => $item->weight,
            'note' => $this->note ?: null,
        ]);

        $item->update(['status' => 'dispatched']);

        $label = $item->huid_code ?: $item->internal_code;
        $this->result = "{$label} dispatched to {$vendor->name}.";
        $this->reset(['selectedItemId', 'itemSearch', 'vendorId', 'expectedReturn', 'note']);
    }

    public function dispatchCustomerMaterial()
    {
        $this->validate([
            'selectedCustomerId' => 'required|exists:customers,id',
            'vendorId' => 'required|exists:vendors,id',
            'description' => 'required|string|max:150',
            'weight' => 'required|numeric|min:0.001',
            'metalType' => 'required|in:gold,silver,titanium,platinum',
            'expectedReturn' => 'nullable|date',
        ]);

        $customer = Customer::findOrFail($this->selectedCustomerId);
        $vendor = Vendor::findOrFail($this->vendorId);

        // Never shop stock — no item ID, tracked against the customer
        // directly rather than through the polymorphic movements table.
        CustomerMaterialJob::create([
            'customer_id' => $customer->id,
            'vendor_id' => $vendor->id,
            'description' => $this->description,
            'weight_out' => $this->weight,
            'metal' => $this->metalType,
            'expected_return' => $this->expectedReturn,
            'status' => 'out',
            'note' => $this->note ?: null,
            'user_id' => Auth::id(),
        ]);

        $this->result = "{$customer->name}'s material ({$this->weight}g {$this->metalType}) dispatched to {$vendor->name}.";
        $this->reset(['selectedCustomerId', 'customerSearch', 'vendorId', 'weight', 'expectedReturn', 'note', 'description']);
        $this->metalType = 'gold';
    }

    public function dispatchRawMaterial()
    {
        $this->validate([
            'vendorId' => 'required|exists:vendors,id',
            'weight' => 'required|numeric|min:0.001',
            'metalType' => 'required|in:gold,silver,titanium,platinum',
            'expectedReturn' => 'nullable|date',
        ]);

        $vendor = Vendor::findOrFail($this->vendorId);

        // What leaves (raw metal) and what returns (finished, untagged
        // piece(s)) are not the same physical thing — tracked in its own
        // table, not the polymorphic movements table. See KarigarReturn's
        // raw-material branch for where the resulting item gets created.
        KarigarRawBatch::create([
            'vendor_id' => $vendor->id,
            'weight_out' => $this->weight,
            'metal' => $this->metalType,
            'purity' => $this->purity ?: null,
            'purpose_label' => $this->purposeLabel ?: null,
            'expected_return' => $this->expectedReturn,
            'status' => 'dispatched',
            'note' => $this->note ?: null,
            'user_id' => Auth::id(),
        ]);

        $this->result = "Raw material ({$this->weight}g {$this->metalType}) issued to {$vendor->name}.";
        $this->reset(['vendorId', 'weight', 'expectedReturn', 'note', 'purity', 'purposeLabel']);
        $this->metalType = 'gold';
    }

    public function render()
    {
        return view('livewire.movement.karigar-dispatch', [
            'itemResults' => $this->itemSearch
                ? Item::where('status', 'in_stock')
                    ->where(fn ($q) => $q->where('huid_code', 'like', "%{$this->itemSearch}%")
                        ->orWhere('internal_code', 'like', "%{$this->itemSearch}%")
                        ->orWhere('category', 'like', "%{$this->itemSearch}%"))
                    ->limit(8)->get()
                : collect(),
            'customerResults' => $this->customerSearch
                ? Customer::where('name', 'like', "%{$this->customerSearch}%")
                    ->orWhere('phone', 'like', "%{$this->customerSearch}%")
                    ->limit(8)->get()
                : collect(),
            'karigars' => Vendor::where('type', 'karigar')->orderBy('name')->get(),
        ])->layout('components.layouts.app', ['title' => 'Karigar Dispatch — Radharani Jewellery']);
    }
}
