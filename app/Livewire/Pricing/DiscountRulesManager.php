<?php
namespace App\Livewire\Pricing;

use App\Models\Pricing\DiscountRule;
use App\Models\Stock\Box;
use App\Models\Stock\Item;
use App\Models\Stock\Packet;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class DiscountRulesManager extends Component
{
    use WithPagination;

    public ?int $editingId = null;

    public string $scope = 'category';
    public ?int $scopeRefId = null;
    public string $category = '';
    public ?float $minWeight = null;
    public ?float $maxWeight = null;
    public string $discountType = 'percentage';
    public float $value = 0;
    public bool $active = true;
    public ?string $validFrom = null;
    public ?string $validTo = null;

    protected function rules(): array
    {
        return [
            'scope' => 'required|in:item,category,box,packet,weight_tier',
            'scopeRefId' => 'nullable|integer',
            'category' => 'nullable|string|max:50',
            'minWeight' => 'nullable|numeric|min:0',
            'maxWeight' => 'nullable|numeric|min:0',
            'discountType' => 'required|in:flat,percentage',
            'value' => 'required|numeric|min:0',
            'validFrom' => 'nullable|date',
            'validTo' => 'nullable|date',
        ];
    }

    public function edit(int $id)
    {
        $r = DiscountRule::findOrFail($id);
        $this->editingId = $r->id;
        $this->scope = $r->scope;
        $this->scopeRefId = $r->scope_ref_id;
        $this->category = (string) $r->category;
        $this->minWeight = $r->min_weight;
        $this->maxWeight = $r->max_weight;
        $this->discountType = $r->discount_type;
        $this->value = $r->value;
        $this->active = $r->active;
        $this->validFrom = $r->valid_from?->toDateString();
        $this->validTo = $r->valid_to?->toDateString();
    }

    public function save()
    {
        $this->validate();

        DiscountRule::updateOrCreate(['id' => $this->editingId], [
            'scope' => $this->scope,
            'scope_ref_id' => in_array($this->scope, ['item', 'packet', 'box']) ? $this->scopeRefId : null,
            'category' => $this->scope === 'category' ? $this->category : null,
            'min_weight' => $this->scope === 'weight_tier' ? $this->minWeight : null,
            'max_weight' => $this->scope === 'weight_tier' ? $this->maxWeight : null,
            'discount_type' => $this->discountType,
            'value' => $this->value,
            'active' => $this->active,
            'valid_from' => $this->validFrom ?: null,
            'valid_to' => $this->validTo ?: null,
            'created_by' => $this->editingId ? DiscountRule::find($this->editingId)->created_by : Auth::id(),
        ]);

        $this->cancel();
        session()->flash('message', 'Discount rule saved.');
    }

    public function cancel()
    {
        $this->reset(['editingId', 'scopeRefId', 'category', 'minWeight', 'maxWeight', 'value', 'validFrom', 'validTo']);
        $this->scope = 'category';
        $this->discountType = 'percentage';
        $this->active = true;
    }

    public function render()
    {
        return view('livewire.pricing.discount-rules-manager', [
            'rules' => DiscountRule::orderByDesc('id')->paginate(15),
            'items' => Item::orderByDesc('id')->limit(50)->get(),
            'packets' => Packet::orderBy('code')->get(),
            'boxes' => Box::orderBy('code')->get(),
        ])->layout('components.layouts.app', ['title' => 'Discount Rules — Radharani Jewellery']);
    }
}
