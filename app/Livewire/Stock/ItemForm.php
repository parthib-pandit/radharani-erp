<?php
namespace App\Livewire\Stock;

use App\Models\Purchase\PurchaseItem;
use App\Models\Stock\Item;
use App\Models\Stock\Packet;
use App\Services\PricingService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Add / Edit Item form, rendered as a modal. Embedded on Inventory and Item
 * Detail, opened from either with:
 *     Livewire.dispatch('open-item-form')                        new piece
 *     Livewire.dispatch('open-item-form', { id: 12 })            edit piece 12
 *     Livewire.dispatch('open-item-form', { purchaseItemId: 4 }) tag a raw-material purchase line
 * Emits 'item-saved' so the host page refreshes.
 */
class ItemForm extends Component
{
    public const METALS = ['gold' => 'Gold', 'silver' => 'Silver', 'platinum' => 'Platinum', 'titanium' => 'Titanium'];

    public const PURITIES = [
        'gold' => ['24K', '22K', '18K', '14K'],
        'silver' => ['99.9', '92.5'],
        'platinum' => ['950', '900'],
        'titanium' => ['Grade 5', 'Grade 2'],
    ];

    public const STATUSES = [
        'in_stock' => 'In stock',
        'dispatched' => 'Dispatched',
        'pending_review' => 'Pending review',
        'reserved' => 'Reserved',
        'sold' => 'Sold',
    ];

    public bool $showForm = false;
    public ?int $editingId = null;
    public ?int $packet_id = null;
    public string $huid_code = '';
    public string $metal = 'gold';
    public string $category = '';
    public string $purity = '';
    public $weight = '';
    public string $description = '';
    public string $hsn_code = '';
    public string $making_type = 'flat_per_piece';
    public $making_value = '';

    // Pairing (earrings / bangles): none | new (create the partner piece now) | existing (link to a piece already entered) | keep
    public string $pairMode = 'none';
    public $partnerWeight = '';
    public string $partnerHuid = '';
    public string $pairSearch = '';
    public ?int $pairWithId = null;
    public ?int $currentPairId = null;

    // Set when tagging a pending raw-material purchase line into a new
    // item — links the created item back to purchase_items.id and flips
    // that line's tag_pending off once saved.
    public ?int $taggingPurchaseItemId = null;

    protected function rules(): array
    {
        return [
            'packet_id' => ['nullable', 'exists:packets,id'],
            'huid_code' => ['nullable', 'string', 'max:20', Rule::unique('items', 'huid_code')->ignore($this->editingId)],
            'metal' => ['required', Rule::in(array_keys(self::METALS))],
            'category' => ['required', 'string', 'max:50'],
            'purity' => ['required', 'string', 'max:10'],
            'weight' => ['required', 'numeric', 'min:0.001', 'max:99999'],
            'description' => ['nullable', 'string', 'max:100'],
            'hsn_code' => ['nullable', 'string', 'max:10'],
            'making_type' => ['required', Rule::in(['percentage', 'flat_per_piece', 'flat_per_gram'])],
            'making_value' => ['required', 'numeric', 'min:0'],
            'pairMode' => ['required', Rule::in(['none', 'new', 'existing', 'keep'])],
            'partnerWeight' => ['required_if:pairMode,new', 'nullable', 'numeric', 'min:0.001'],
            'partnerHuid' => ['nullable', 'string', 'max:20', 'different:huid_code', Rule::unique('items', 'huid_code')],
            'pairWithId' => ['required_if:pairMode,existing', 'nullable', 'exists:items,id'],
        ];
    }

    protected $validationAttributes = [
        'huid_code' => 'HUID',
        'packet_id' => 'packet',
        'making_value' => 'making charge',
        'partnerWeight' => 'partner piece weight',
        'partnerHuid' => 'partner HUID',
        'pairWithId' => 'piece to pair with',
    ];

    protected $messages = [
        'partnerWeight.required_if' => 'Enter the weight of the second piece. Pairs are weighed separately.',
        'pairWithId.required_if' => 'Pick the piece this one pairs with.',
    ];

    #[On('open-item-form')]
    public function open(?int $id = null, ?int $purchaseItemId = null): void
    {
        $this->resetForm();

        if ($id) {
            $this->loadItem($id);
        } elseif ($purchaseItemId) {
            $this->loadPurchaseLine($purchaseItemId);
        }

        $this->showForm = true;
    }

    private function resetForm(): void
    {
        $this->resetValidation();
        $this->reset([
            'editingId', 'packet_id', 'huid_code', 'category', 'purity', 'weight', 'description', 'hsn_code',
            'making_value', 'pairMode', 'partnerWeight', 'partnerHuid', 'pairSearch', 'pairWithId', 'currentPairId',
            'taggingPurchaseItemId',
        ]);
        $this->metal = 'gold';
        $this->making_type = 'flat_per_piece';
    }

    private function loadItem(int $id): void
    {
        $item = Item::findOrFail($id);
        $this->editingId = $item->id;
        $this->packet_id = $item->packet_id;
        $this->huid_code = (string) $item->huid_code;
        $this->metal = $item->metal ?? 'gold';
        $this->category = $item->category;
        $this->purity = $item->purity;
        $this->weight = (string) (float) $item->weight;
        $this->description = (string) $item->description;
        $this->hsn_code = (string) $item->hsn_code;
        $this->making_type = in_array($item->making_type, ['percentage', 'flat_per_piece', 'flat_per_gram'], true) ? $item->making_type : 'flat_per_piece';
        $this->making_value = (string) (float) $item->making_value;
        $this->currentPairId = $item->pair_group_id ? $item->pairedWith()->value('id') : null;
        $this->pairMode = $this->currentPairId ? 'keep' : 'none';
    }

    // Pre-fills the form from a pending raw-material purchase line so staff
    // don't retype the description/category/metal/purity.
    private function loadPurchaseLine(int $purchaseItemId): void
    {
        $line = PurchaseItem::where('tag_pending', true)->findOrFail($purchaseItemId);

        $this->taggingPurchaseItemId = $line->id;
        $this->metal = $line->metal ?? 'gold';
        $this->category = (string) $line->category;
        $this->purity = (string) $line->purity;
        $this->weight = $line->weight ? (string) (float) $line->weight : '';
        $this->description = (string) $line->description;
    }

    public function updatedMetal(): void
    {
        // Purity formats differ by metal (22K vs 92.5), so a stale value would be wrong.
        if ($this->purity && ! in_array($this->purity, self::PURITIES[$this->metal] ?? [], true)) {
            $this->purity = '';
        }
    }

    public function updatedPairMode(): void
    {
        if ($this->pairMode === 'new' && $this->partnerWeight === '') {
            $this->partnerWeight = $this->weight;
        }
    }

    public function save(): void
    {
        $this->huid_code = strtoupper(trim($this->huid_code));
        $this->partnerHuid = strtoupper(trim($this->partnerHuid));
        $this->packet_id = $this->packet_id ?: null;
        $this->validate();

        $data = [
            'packet_id' => $this->packet_id,
            'huid_code' => $this->huid_code ?: null,
            'metal' => $this->metal,
            'category' => trim($this->category),
            'purity' => trim($this->purity),
            'weight' => $this->weight,
            'description' => $this->description ?: null,
            'hsn_code' => $this->hsn_code ?: null,
            'making_type' => $this->making_type,
            'making_value' => $this->making_value,
        ];

        $item = DB::transaction(function () use ($data) {
            if ($this->editingId) {
                $item = Item::findOrFail($this->editingId);
                // A piece that lost its HUID still needs a readable code.
                if (! $data['huid_code'] && ! $item->internal_code) {
                    $data['internal_code'] = Item::generateInternalCode();
                }
                $item->update($data);
            } else {
                if ($this->taggingPurchaseItemId) {
                    $data['source_purchase_item_id'] = $this->taggingPurchaseItemId;
                }
                // No HUID -> auto-generate a unique internal code from Item's curated non-ambiguous charset.
                if (! $data['huid_code']) {
                    $data['internal_code'] = Item::generateInternalCode();
                }
                $item = Item::create($data + ['status' => 'in_stock']);
            }

            if ($this->taggingPurchaseItemId) {
                PurchaseItem::where('id', $this->taggingPurchaseItemId)->update([
                    'tag_pending' => false,
                    'item_id' => $item->id,
                ]);
            }

            $this->applyPairing($item, $data);

            return $item;
        });

        $wasEditing = (bool) $this->editingId;
        $this->showForm = false;
        $this->resetForm();
        $this->dispatch('item-saved', id: $item->id);
        $this->dispatch('toast', message: $wasEditing ? "{$item->label} updated." : "{$item->label} added to stock.", type: 'success');
    }

    // Pairs share pair_group_id; each physical piece keeps its own row and weight.
    private function applyPairing(Item $item, array $data): void
    {
        if ($this->pairMode === 'keep') {
            return;
        }

        // Pairing changed on an edit: detach from the old partner first.
        if ($this->editingId && $this->currentPairId) {
            Item::whereKey($this->currentPairId)->first()?->update(['pair_group_id' => null]);
            $item->update(['pair_group_id' => null]);
        }

        if ($this->pairMode === 'new') {
            $item->update(['pair_group_id' => $item->id]);
            $partner = $data;
            $partner['weight'] = $this->partnerWeight;
            $partner['huid_code'] = $this->partnerHuid ?: null;
            $partner['internal_code'] = $this->partnerHuid ? null : Item::generateInternalCode();
            $partner['pair_group_id'] = $item->id;
            unset($partner['source_purchase_item_id']);
            Item::create($partner + ['status' => 'in_stock']);
        }

        if ($this->pairMode === 'existing' && $this->pairWithId && $this->pairWithId !== $item->id) {
            $other = Item::findOrFail($this->pairWithId);
            $group = $other->pair_group_id ?: min($other->id, $item->id);
            $other->update(['pair_group_id' => $group]);
            $item->update(['pair_group_id' => $group]);
        }
    }

    public function render()
    {
        return view('livewire.stock.item-form', [
            'categories' => $this->showForm ? Item::query()->distinct()->orderBy('category')->pluck('category') : collect(),
            'packetsByBox' => $this->showForm
                ? Packet::with('box:id,code')->orderBy('code')->get(['id', 'code', 'label', 'box_id'])->groupBy(fn ($p) => $p->box?->code ?? 'Not in a box')
                : collect(),
            'pairCandidates' => $this->showForm && $this->pairMode === 'existing'
                ? Item::whereNull('pair_group_id')
                    ->when($this->editingId, fn ($q) => $q->where('id', '!=', $this->editingId))
                    ->when($this->pairSearch, fn ($q) => $q->where(fn ($q) => $q
                        ->where('huid_code', 'like', "%{$this->pairSearch}%")
                        ->orWhere('internal_code', 'like', "%{$this->pairSearch}%")
                        ->orWhere('category', 'like', "%{$this->pairSearch}%")),
                        fn ($q) => $q->when($this->category, fn ($q) => $q->where('category', $this->category)))
                    ->where('status', '!=', 'sold')
                    ->orderByDesc('id')->limit(25)->get()
                : collect(),
            'currentPair' => $this->currentPairId ? Item::find($this->currentPairId) : null,
            'estimate' => $this->showForm ? $this->estimate() : null,
        ]);
    }

    // Live price preview using today's rate. Nothing is stored.
    private function estimate(): ?array
    {
        if (! is_numeric($this->weight) || $this->weight <= 0 || ! is_numeric($this->making_value)) {
            return null;
        }

        $draft = new Item([
            'metal' => $this->metal,
            'purity' => $this->purity,
            'weight' => $this->weight,
            'category' => $this->category,
            'huid_code' => $this->huid_code ?: null,
            'making_type' => $this->making_type,
            'making_value' => $this->making_value,
            'packet_id' => $this->packet_id,
        ]);
        if ($this->editingId) {
            $draft->id = $this->editingId;
        }

        return app(PricingService::class)->breakdown($draft);
    }
}
