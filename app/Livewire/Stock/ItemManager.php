<?php
namespace App\Livewire\Stock;

use App\Models\Stock\Item;
use App\Models\Stock\Packet;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class ItemManager extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';
    public ?int $editingId = null;

    public ?int $packet_id = null;
    public string $huid_code = '';
    public string $category = '';
    public string $purity = '';
    public float $weight = 0;
    public string $description = '';
    public string $hsn_code = '';
    public string $making_type = 'per_piece';
    public float $making_value = 0;
    public bool $has_pair = false;

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }

    protected function rules(): array
    {
        return [
            'packet_id' => 'nullable|exists:packets,id',
            'huid_code' => 'nullable|string|max:20',
            'category' => 'required|string|max:50',
            'purity' => 'required|string|max:10',
            'weight' => 'required|numeric|min:0.001',
            'description' => 'nullable|string|max:100',
            'hsn_code' => 'nullable|string|max:10',
            'making_type' => 'required|in:per_piece,percentage',
            'making_value' => 'required|numeric|min:0',
        ];
    }

    public function edit(int $id)
    {
        $item = Item::findOrFail($id);
        $this->editingId = $item->id;
        $this->packet_id = $item->packet_id;
        $this->huid_code = (string) $item->huid_code;
        $this->category = $item->category;
        $this->purity = $item->purity;
        $this->weight = $item->weight;
        $this->description = (string) $item->description;
        $this->hsn_code = (string) $item->hsn_code;
        $this->making_type = $item->making_type;
        $this->making_value = $item->making_value;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'packet_id' => $this->packet_id,
            'huid_code' => $this->huid_code ?: null,
            'category' => $this->category,
            'purity' => $this->purity,
            'weight' => $this->weight,
            'description' => $this->description,
            'hsn_code' => $this->hsn_code,
            'making_type' => $this->making_type,
            'making_value' => $this->making_value,
        ];

        // No HUID → auto-generate a 7-character fallback code, unique.
        if (empty($data['huid_code']) && ! $this->editingId) {
            do {
                $candidate = strtoupper(Str::random(7));
            } while (Item::where('internal_code', $candidate)->exists());
            $data['internal_code'] = $candidate;
        }

        $item = Item::updateOrCreate(['id' => $this->editingId], $data);

        // Pair (e.g. earrings): create a second linked row sharing pair_group_id.
        if ($this->has_pair && ! $this->editingId) {
            $item->pair_group_id = $item->pair_group_id ?? $item->id;
            $item->save();

            $partnerData = $data;
            $partnerData['pair_group_id'] = $item->pair_group_id;
            unset($partnerData['internal_code']);
            if (empty($data['huid_code'])) {
                do {
                    $candidate = strtoupper(Str::random(7));
                } while (Item::where('internal_code', $candidate)->exists());
                $partnerData['internal_code'] = $candidate;
            }
            Item::create($partnerData);
        }

        $this->reset([
            'editingId', 'packet_id', 'huid_code', 'category', 'purity',
            'weight', 'description', 'hsn_code', 'making_type', 'making_value', 'has_pair',
        ]);
        $this->making_type = 'per_piece';

        session()->flash('message', 'Item saved.');
    }

    public function cancel()
    {
        $this->reset([
            'editingId', 'packet_id', 'huid_code', 'category', 'purity',
            'weight', 'description', 'hsn_code', 'making_type', 'making_value', 'has_pair',
        ]);
        $this->making_type = 'per_piece';
    }

    public function render()
    {
        $items = Item::with('packet.box')
            ->when($this->search, fn ($q) => $q
                ->where('huid_code', 'like', "%{$this->search}%")
                ->orWhere('internal_code', 'like', "%{$this->search}%")
                ->orWhere('category', 'like', "%{$this->search}%"))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->orderByDesc('id')
            ->paginate(15);

        return view('livewire.stock.item-manager', [
            'items' => $items,
            'packets' => Packet::orderBy('code')->get(),
        ]);
    }
}
