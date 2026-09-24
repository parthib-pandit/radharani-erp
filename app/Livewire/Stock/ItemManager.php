<?php
namespace App\Livewire\Stock;

use App\Models\Purchase\PurchaseItem;
use App\Models\Stock\Item;
use App\Models\Stock\Packet;
use Livewire\Component;
use Livewire\WithPagination;

class ItemManager extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';
    public string $metalFilter = '';
    public ?int $editingId = null;

    public ?int $packet_id = null;
    public string $huid_code = '';
    public string $metal = 'gold';
    public string $category = '';
    public string $purity = '';
    public float $weight = 0;
    public string $description = '';
    public string $hsn_code = '';
    public string $making_type = 'flat_per_piece';
    public float $making_value = 0;
    public bool $has_pair = false;

    // Set when tagging a pending raw-material purchase line into a new
    // item — links the created item back to purchase_items.id and flips
    // that line's tag_pending off once saved.
    public ?int $taggingPurchaseItemId = null;

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }
    public function updatingMetalFilter() { $this->resetPage(); }

    protected function rules(): array
    {
        return [
            'packet_id' => 'nullable|exists:packets,id',
            'huid_code' => 'nullable|string|max:20',
            'metal' => 'required|in:gold,silver,titanium,platinum',
            'category' => 'required|string|max:50',
            'purity' => 'required|string|max:10',
            'weight' => 'required|numeric|min:0.001',
            'description' => 'nullable|string|max:100',
            'hsn_code' => 'nullable|string|max:10',
            'making_type' => 'required|in:percentage,flat_per_piece,flat_per_gram',
            'making_value' => 'required|numeric|min:0',
        ];
    }

    public function edit(int $id)
    {
        $item = Item::findOrFail($id);
        $this->editingId = $item->id;
        $this->packet_id = $item->packet_id;
        $this->huid_code = (string) $item->huid_code;
        $this->metal = $item->metal ?? 'gold';
        $this->category = $item->category;
        $this->purity = $item->purity;
        $this->weight = $item->weight;
        $this->description = (string) $item->description;
        $this->hsn_code = (string) $item->hsn_code;
        $this->making_type = $item->making_type;
        $this->making_value = $item->making_value;
    }

    // Pre-fills the New Item form from a pending raw-material purchase line
    // so staff don't retype the description/category/metal/purity. The
    // form otherwise behaves exactly like a normal New Item save.
    public function tagFromPurchaseLine(int $purchaseItemId)
    {
        $line = PurchaseItem::findOrFail($purchaseItemId);

        $this->cancel();
        $this->taggingPurchaseItemId = $line->id;
        $this->metal = $line->metal ?? 'gold';
        $this->category = (string) $line->category;
        $this->purity = (string) $line->purity;
        $this->weight = (float) $line->weight;
        $this->description = (string) $line->description;
    }

    public function cancelTagging()
    {
        $this->cancel();
    }

    public function save()
    {
        $this->validate();

        $data = [
            'packet_id' => $this->packet_id,
            'huid_code' => $this->huid_code ?: null,
            'metal' => $this->metal,
            'category' => $this->category,
            'purity' => $this->purity,
            'weight' => $this->weight,
            'description' => $this->description,
            'hsn_code' => $this->hsn_code,
            'making_type' => $this->making_type,
            'making_value' => $this->making_value,
        ];

        if ($this->taggingPurchaseItemId) {
            $data['source_purchase_item_id'] = $this->taggingPurchaseItemId;
        }

        // No HUID → auto-generate a unique internal code from Item's
        // curated non-ambiguous charset.
        if (empty($data['huid_code']) && ! $this->editingId) {
            $data['internal_code'] = Item::generateInternalCode();
        }

        $item = Item::updateOrCreate(['id' => $this->editingId], $data);

        if ($this->taggingPurchaseItemId) {
            PurchaseItem::where('id', $this->taggingPurchaseItemId)->update([
                'tag_pending' => false,
                'item_id' => $item->id,
            ]);
        }

        // Pair (e.g. earrings): create a second linked row sharing pair_group_id.
        if ($this->has_pair && ! $this->editingId) {
            $item->pair_group_id = $item->pair_group_id ?? $item->id;
            $item->save();

            $partnerData = $data;
            $partnerData['pair_group_id'] = $item->pair_group_id;
            unset($partnerData['internal_code']);
            if (empty($data['huid_code'])) {
                $partnerData['internal_code'] = Item::generateInternalCode();
            }
            Item::create($partnerData);
        }

        $this->reset([
            'editingId', 'packet_id', 'huid_code', 'metal', 'category', 'purity',
            'weight', 'description', 'hsn_code', 'making_type', 'making_value',
            'has_pair', 'taggingPurchaseItemId',
        ]);
        $this->making_type = 'flat_per_piece';
        $this->metal = 'gold';

        session()->flash('message', 'Item saved.');
    }

    public function cancel()
    {
        $this->reset([
            'editingId', 'packet_id', 'huid_code', 'metal', 'category', 'purity',
            'weight', 'description', 'hsn_code', 'making_type', 'making_value',
            'has_pair', 'taggingPurchaseItemId',
        ]);
        $this->making_type = 'flat_per_piece';
        $this->metal = 'gold';
    }

    public function render()
    {
        $items = Item::with('packet.box')
            ->when($this->search, fn ($q) => $q
                ->where('huid_code', 'like', "%{$this->search}%")
                ->orWhere('internal_code', 'like', "%{$this->search}%")
                ->orWhere('category', 'like', "%{$this->search}%"))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->when($this->metalFilter, fn ($q) => $q->where('metal', $this->metalFilter))
            ->orderByDesc('id')
            ->paginate(15);

        return view('livewire.stock.item-manager', [
            'items' => $items,
            'packets' => Packet::orderBy('code')->get(),
            'pendingTags' => PurchaseItem::where('tag_pending', true)
                ->whereNull('item_id')
                ->with('purchase.vendor')
                ->orderByDesc('id')
                ->get(),
        ])->layout('components.layouts.app', ['title' => 'Inventory — Radharani Jewellery ERP']);
    }
}
