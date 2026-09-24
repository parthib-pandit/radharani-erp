<?php
namespace App\Livewire\Storefront;

use App\Models\Stock\Item;
use App\Services\PricingService;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Catalog / Product Listing — public, no login required.
 *
 * FLAG: items has no photo column (and no product-photos table) — only
 * movements.photo_path exists, which is a dispatch-documentation photo,
 * not a catalog photo. Cards below show a placeholder swatch instead of a
 * fabricated image. Pricing is real and live (PricingService::priceFor),
 * never stored, same as everywhere else.
 */
class CatalogListing extends Component
{
    use WithPagination;

    public string $categoryFilter = 'all';
    public string $search = '';

    public function updatingCategoryFilter() { $this->resetPage(); }
    public function updatingSearch() { $this->resetPage(); }

    public function render()
    {
        $pricing = app(PricingService::class);

        $items = Item::where('status', 'in_stock')
            ->when($this->categoryFilter !== 'all', fn ($q) => $q->where('category', $this->categoryFilter))
            ->when($this->search, fn ($q) => $q->where('category', 'like', "%{$this->search}%")
                ->orWhere('description', 'like', "%{$this->search}%"))
            ->orderByDesc('id')
            ->paginate(12);

        $categories = Item::where('status', 'in_stock')->distinct()->pluck('category');

        return view('livewire.storefront.catalog-listing', [
            'items' => $items,
            'categories' => $categories,
            'pricing' => $pricing,
        ])->layout('components.layouts.guest');
    }
}
