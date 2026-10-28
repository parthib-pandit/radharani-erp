<?php
namespace App\Livewire\Stock;

use App\Models\Stock\Box;
use App\Models\Stock\Item;
use App\Models\Stock\Packet;
use App\Support\StockCodes;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * Box -> Packet -> Piece workspace for bigger reorganisations: three columns,
 * drag-and-drop (or tick + "Move to") between them, create/rename in place.
 * Every move is a per-model save so it's recorded in the history log.
 *
 * Selections use 'none' for the "not in a box" / "not in a packet" buckets.
 */
class HierarchyConfigurator extends Component
{
    #[Url(as: 'box', except: '')]
    public string $boxId = '';
    #[Url(as: 'packet', except: '')]
    public string $packetId = '';

    public string $boxSearch = '';
    public string $packetSearch = '';
    public string $itemSearch = '';

    // Each column shows 10 at first (28 Oct, so it stays light on a phone). Load more adds 10, View all lifts the limit.
    public const PAGE = 10;
    public array $limit = ['box' => self::PAGE, 'packet' => self::PAGE, 'item' => self::PAGE];
    public array $all = ['box' => false, 'packet' => false, 'item' => false];

    public function loadMore(string $column): void
    {
        if (isset($this->limit[$column])) {
            $this->limit[$column] += self::PAGE;
        }
    }

    public function viewAll(string $column): void
    {
        if (isset($this->all[$column])) {
            $this->all[$column] = true;
        }
    }

    public function showFewer(string $column): void
    {
        if (isset($this->all[$column])) {
            $this->all[$column] = false;
            $this->limit[$column] = self::PAGE;
        }
    }

    // A new search starts that column from its first ten again.
    public function updatedBoxSearch(): void
    {
        $this->showFewer('box');
    }

    public function updatedPacketSearch(): void
    {
        $this->showFewer('packet');
    }

    public function updatedItemSearch(): void
    {
        $this->showFewer('item');
    }

    public array $selectedPackets = [];
    public array $selectedItems = [];

    // Create / rename modal (shared by boxes and packets)
    public bool $showForm = false;
    public string $formKind = 'box'; // box | packet
    public ?int $formId = null;
    public string $formCode = '';
    public string $formLabel = '';

    public function mount(): void
    {
        if ($this->boxId === '') {
            $this->boxId = (string) (Box::orderBy('code')->value('id') ?? 'none');
        }
        if ($this->packetId === '') {
            $this->openFirstPacket();
        }
    }

    // Keep the pieces column useful: land on the box's first packet.
    private function openFirstPacket(): void
    {
        $first = Packet::when($this->boxId === 'none', fn ($q) => $q->whereNull('box_id'), fn ($q) => $q->where('box_id', (int) $this->boxId))
            ->orderBy('code')->value('id');
        $this->packetId = $first ? (string) $first : '';
    }

    public function selectBox(string $id): void
    {
        $this->boxId = $id;
        $this->selectedPackets = [];
        $this->selectedItems = [];
        $this->packetSearch = '';
        $this->showFewer('packet');
        $this->showFewer('item');
        $this->openFirstPacket();
    }

    public function selectPacket(string $id): void
    {
        $this->packetId = $id;
        $this->selectedItems = [];
        $this->itemSearch = '';
        $this->showFewer('item');
    }

    // ------------------------------------------------------------- moves

    public function movePackets(array $ids, $toBoxId): void
    {
        $to = $toBoxId ? Box::findOrFail((int) $toBoxId) : null;
        $moved = 0;
        foreach (Packet::whereIn('id', $ids)->get() as $packet) {
            if ($packet->box_id !== $to?->id) {
                $packet->update(['box_id' => $to?->id]);
                $moved++;
            }
        }
        $this->selectedPackets = [];
        $this->dispatch('toast', message: $moved ? "{$moved} packet(s) moved to " . ($to ? $to->code : 'no box') . '.' : 'Already there.', type: $moved ? 'success' : 'info');
    }

    public function moveItems(array $ids, $toPacketId): void
    {
        $to = $toPacketId ? Packet::findOrFail((int) $toPacketId) : null;
        $moved = 0;
        foreach (Item::whereIn('id', $ids)->where('status', '!=', 'sold')->get() as $item) {
            if ($item->packet_id !== $to?->id) {
                $item->update(['packet_id' => $to?->id]);
                $moved++;
            }
        }
        $this->selectedItems = [];
        $this->dispatch('toast', message: $moved ? "{$moved} piece(s) moved to " . ($to ? $to->code : 'no packet') . '.' : 'Already there.', type: $moved ? 'success' : 'info');
    }

    // ------------------------------------------------------ create / rename

    public function openCreate(string $kind): void
    {
        $this->resetValidation();
        $this->formKind = $kind === 'packet' ? 'packet' : 'box';
        $this->formId = null;
        $this->formLabel = '';
        $this->formCode = $this->formKind === 'box'
            ? StockCodes::next(Box::class, 'BOX-')
            : StockCodes::next(Packet::class, ctype_digit($this->boxId) ? "PKT-{$this->boxId}-" : 'PKT-');
        $this->showForm = true;
    }

    public function openRename(string $kind, int $id): void
    {
        $this->resetValidation();
        $model = $kind === 'packet' ? Packet::findOrFail($id) : Box::findOrFail($id);
        $this->formKind = $kind === 'packet' ? 'packet' : 'box';
        $this->formId = $model->id;
        $this->formCode = $model->code;
        $this->formLabel = (string) $model->label;
        $this->showForm = true;
    }

    public function saveForm(): void
    {
        $table = $this->formKind === 'box' ? 'boxes' : 'packets';
        $this->formCode = strtoupper(trim($this->formCode));
        $this->validate([
            'formCode' => ['required', 'string', 'max:50', Rule::unique($table, 'code')->ignore($this->formId)],
            'formLabel' => ['nullable', 'string', 'max:100'],
        ], [], ['formCode' => $this->formKind . ' code', 'formLabel' => 'label']);

        $data = ['code' => $this->formCode, 'label' => $this->formLabel ?: null];

        if ($this->formKind === 'box') {
            $box = $this->formId ? tap(Box::findOrFail($this->formId))->update($data) : Box::create($data);
            if (! $this->formId) {
                $this->selectBox((string) $box->id);
            }
        } else {
            if ($this->formId) {
                Packet::findOrFail($this->formId)->update($data);
            } else {
                $packet = Packet::create($data + ['box_id' => ctype_digit($this->boxId) ? (int) $this->boxId : null]);
                $this->packetId = (string) $packet->id;
            }
        }

        $verb = $this->formId ? 'saved' : 'created';
        $this->showForm = false;
        $this->dispatch('toast', message: ucfirst($this->formKind) . " {$this->formCode} {$verb}.", type: 'success');
    }

    // -------------------------------------------------------------- render

    public function render()
    {
        $boxRows = Box::withCount(['packets', 'custodyItems as items_count'])
            ->when($this->boxSearch, fn ($q) => $q->where(fn ($q) => $q->where('code', 'like', "%{$this->boxSearch}%")->orWhere('label', 'like', "%{$this->boxSearch}%")))
            ->orderBy('code')->get();

        $packetRows = Packet::withCount('custodyItems as items_count')->withSum('custodyItems as items_sum_weight', 'weight')
            ->when($this->boxId === 'none', fn ($q) => $q->whereNull('box_id'), fn ($q) => $q->where('box_id', (int) $this->boxId))
            ->when($this->packetSearch, fn ($q) => $q->where(fn ($q) => $q->where('code', 'like', "%{$this->packetSearch}%")->orWhere('label', 'like', "%{$this->packetSearch}%")))
            ->orderBy('code')->get();

        $itemQuery = $this->packetId === ''
            ? null
            : Item::query()
                ->when($this->packetId === 'none', fn ($q) => $q->whereNull('packet_id')->where('status', '!=', 'sold'), fn ($q) => $q->where('packet_id', (int) $this->packetId)->where('status', '!=', 'sold'))
                ->when($this->itemSearch, fn ($q) => $q->where(fn ($q) => $q
                    ->where('huid_code', 'like', "%{$this->itemSearch}%")
                    ->orWhere('internal_code', 'like', "%{$this->itemSearch}%")
                    ->orWhere('category', 'like', "%{$this->itemSearch}%")));
        $itemTotal = $itemQuery ? (clone $itemQuery)->count() : 0;
        $items = $itemQuery
            ? $itemQuery->orderBy('category')->orderBy('id')->limit($this->all['item'] ? 1000 : $this->limit['item'])->get()
            : collect();

        // The first ten (or as many as were asked for), and the one that is open always stays in the list.
        $slice = function ($rows, string $column, ?string $openId) {
            $shown = $this->all[$column] ? $rows : $rows->take($this->limit[$column]);
            if (ctype_digit((string) $openId) && ! $shown->contains('id', (int) $openId) && ($open = $rows->firstWhere('id', (int) $openId))) {
                $shown = $shown->prepend($open);
            }

            return $shown->values();
        };
        $boxes = $slice($boxRows, 'box', $this->boxId);
        $packets = $slice($packetRows, 'packet', $this->packetId);

        return view('livewire.stock.hierarchy-configurator', [
            'boxes' => $boxes,
            'packets' => $packets,
            'items' => $items,
            'totals' => ['box' => $boxRows->count(), 'packet' => $packetRows->count(), 'item' => $itemTotal],
            'currentBox' => ctype_digit($this->boxId) ? Box::find((int) $this->boxId) : null,
            'currentPacket' => ctype_digit($this->packetId) ? Packet::find((int) $this->packetId) : null,
            'unboxedCount' => Packet::whereNull('box_id')->count(),
            'unpackedCount' => Item::whereNull('packet_id')->where('status', '!=', 'sold')->count(),
            'allBoxes' => Box::orderBy('code')->get(['id', 'code']),
            'allPackets' => Packet::with('box:id,code')->orderBy('code')->get(['id', 'code', 'box_id']),
        ])->layout('components.layouts.app', ['title' => 'Configurator · Radharani Jewellery']);
    }
}
