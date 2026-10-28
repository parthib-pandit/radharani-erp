<?php
namespace App\Livewire\Stock;

use App\Livewire\Concerns\WithDataTable;
use App\Models\Location;
use App\Models\Stock\Item;
use App\Models\Stock\ItemCategory;
use Livewire\Component;

/**
 * One subcategory (28 Oct): every piece in it, in a plain table, with the box and
 * packet it sits in and where it is right now (vault, tray, counter, or why it is away).
 */
class CategoryDetail extends Component
{
    use WithDataTable;

    public ItemCategory $category;

    protected function sortableColumns(): array
    {
        return ['code' => 'items.internal_code', 'weight' => 'items.weight', 'status' => 'items.status'];
    }

    protected function defaultSort(): array
    {
        return ['code', 'asc'];
    }

    public function mount(ItemCategory $category): void
    {
        $this->category = $category;
    }

    public function render()
    {
        $term = trim($this->search);
        $rows = $this->applySorting(
            Item::with('packet.box')->where('category_id', $this->category->id)
                ->when($term !== '', fn ($q) => $q->where(fn ($w) => $w
                    ->where('huid_code', 'like', "%{$term}%")->orWhere('internal_code', 'like', "%{$term}%")->orWhere('description', 'like', "%{$term}%")
                    ->orWhereIn('packet_id', \App\Models\Stock\Packet::query()->select('id')->where('code', 'like', "%{$term}%")
                        ->orWhereIn('box_id', \App\Models\Stock\Box::query()->select('id')->where('code', 'like', "%{$term}%")))))
        )->paginate($this->perPageValue());

        // Where each in-stock piece is, worked out once for the shop.
        $placeIds = $rows->getCollection()->contains('status', 'in_stock') ? Location::itemLocations() : [];
        $names = Location::pluck('name', 'id');
        $places = $rows->getCollection()->mapWithKeys(fn ($item) => [$item->id => match ($item->status) {
            'in_stock', 'reserved' => $names[$placeIds[$item->id] ?? 0] ?? ($item->status === 'reserved' ? 'Held for a sale' : 'Vault'),
            'dispatched' => 'Out of the shop',
            'pending_review' => 'Waiting for review',
            'on_hold' => 'Not in a packet',
            default => '-',
        }]);

        $stats = Item::where('category_id', $this->category->id)->where('status', '!=', 'sold')
            ->selectRaw('COUNT(*) as pieces, COALESCE(SUM(weight), 0) as weight')->first();

        return view('livewire.stock.category-detail', ['rows' => $rows, 'places' => $places, 'stats' => $stats])
            ->layout('components.layouts.app', ['title' => "{$this->category->name} · Radharani Jewellery ERP"]);
    }
}
