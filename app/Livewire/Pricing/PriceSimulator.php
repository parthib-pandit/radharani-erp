<?php
namespace App\Livewire\Pricing;

use App\Livewire\Stock\ItemForm;
use App\Models\Stock\Item;
use App\Models\Stock\ItemCategory;
use App\Services\PricingService;
use Livewire\Component;

/**
 * Price simulator (8 Oct change list, 11.3): read-only. Enter a piece's details and see the
 * price the system would work out, line by line. Nothing is saved.
 */
class PriceSimulator extends Component
{
    public string $metal = 'gold';
    public string $purity = '22K';
    public $weight = '10';
    public $net_weight = '';
    public ?int $categoryId = null;
    public string $making_type = 'percentage';
    public $making_value = '12';
    public $stone_value = '';
    public bool $has_huid = true;   // the hallmarking charge applies to pieces that carry a HUID only

    // The making charge bargained down (the slider), as a rate. Empty means the rate the pricing gives. Shown inside the dashboard too.
    public $bargain = '';
    public bool $embedded = false;

    public function mount(bool $embedded = false): void
    {
        $this->embedded = $embedded;
    }

    // A different piece or making setting means a different pricing rate, so the bargain starts again.
    public function updated(string $name): void
    {
        if (in_array($name, ['metal', 'purity', 'categoryId', 'making_type', 'making_value'], true)) {
            $this->bargain = '';
        }
    }

    public function setBargain($rate): void
    {
        $this->bargain = is_numeric($rate) ? (string) $rate : '';
    }

    public function updatedMetal(): void
    {
        $this->purity = (ItemForm::PURITIES[$this->metal] ?? [''])[0];
        $this->categoryId = null;
    }

    public function render(PricingService $pricing)
    {
        $result = null;
        if (is_numeric($this->weight) && (float) $this->weight > 0) {
            $draft = new Item([
                'metal' => $this->metal,
                'purity' => $this->purity,
                'weight' => (float) $this->weight,
                'net_weight' => is_numeric($this->net_weight) && (float) $this->net_weight > 0 ? (float) $this->net_weight : null,
                'huid_code' => $this->has_huid ? 'SIMULATED' : null,
                'category' => (string) ItemCategory::find($this->categoryId)?->name,
                'making_type' => $this->making_type,
                'making_value' => is_numeric($this->making_value) ? (float) $this->making_value : 0,
                'stone_value' => is_numeric($this->stone_value) ? (float) $this->stone_value : 0,
            ]);
            $result = $pricing->breakdown($draft, ['making_rate' => is_numeric($this->bargain) ? (float) $this->bargain : null]);
        }

        $gst = null;
        if ($result) {
            [$jr, $hr] = $pricing->gstRates();
            $gst = $pricing->gstOnItem($result, $jr, $hr) + ['rates' => [$jr, $hr]];
        }

        return view('livewire.pricing.price-simulator', [
            'result' => $result,
            'gst' => $gst,
            'canSell' => (bool) auth()->user()?->can('sale.create'),
            'metals' => ItemForm::METALS,
            'purities' => ItemForm::PURITIES[$this->metal] ?? [],
            'categories' => ItemCategory::active()->forMetal($this->metal)->orderBy('sort_order')->orderBy('name')->get(['id', 'name']),
        ])->layout('components.layouts.app', ['title' => 'Price simulator · Radharani Jewellery']);
    }
}
