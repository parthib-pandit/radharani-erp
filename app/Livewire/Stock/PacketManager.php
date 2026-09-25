<?php
namespace App\Livewire\Stock;

use App\Livewire\Concerns\WithDataTable;
use App\Models\Stock\Box;
use App\Models\Stock\Item;
use App\Models\Stock\Packet;
use App\Models\Stock\QrCode;
use App\Support\StockCodes;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;

class PacketManager extends Component
{
    use WithDataTable;

    #[Url(as: 'box', except: '')]
    public string $boxFilter = ''; // '' | box id | 'none'

    #[Url(except: '')]
    public string $contents = ''; // '' | empty | filled

    // Add / edit modal
    public bool $showForm = false;
    public ?int $editingId = null;
    public string $code = '';
    public string $label = '';
    public ?int $box_id = null;
    public string $suggestedCode = '';

    // Bulk move modal
    public bool $showMove = false;
    public ?int $moveToBoxId = null;

    protected function sortableColumns(): array
    {
        return [
            'code' => 'code',
            'box' => 'box_code',
            'items' => 'items_count',
            'weight' => 'items_sum_weight',
            'created' => 'packets.created_at',
        ];
    }

    protected function defaultSort(): array
    {
        return ['code', 'asc'];
    }

    protected function filterProperties(): array
    {
        return ['boxFilter', 'contents'];
    }

    protected function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50', Rule::unique('packets', 'code')->ignore($this->editingId)],
            'label' => ['nullable', 'string', 'max:100'],
            'box_id' => ['nullable', 'exists:boxes,id'],
        ];
    }

    protected $validationAttributes = ['code' => 'packet code', 'box_id' => 'box'];

    public function create(?int $boxId = null): void
    {
        $this->resetValidation();
        $this->reset(['editingId', 'label']);
        $this->box_id = $boxId ?: (ctype_digit($this->boxFilter) ? (int) $this->boxFilter : null);
        $this->code = $this->suggestedCode = $this->suggestCode();
        $this->showForm = true;
    }

    // While creating, keep the suggested code in step with the chosen box
    // unless staff have already typed their own.
    public function updatedBoxId(): void
    {
        if (! $this->editingId && $this->code === $this->suggestedCode) {
            $this->code = $this->suggestedCode = $this->suggestCode();
        }
    }

    private function suggestCode(): string
    {
        return StockCodes::next(Packet::class, $this->box_id ? "PKT-{$this->box_id}-" : 'PKT-');
    }

    public function edit(int $id): void
    {
        $this->resetValidation();
        $packet = Packet::findOrFail($id);
        $this->editingId = $packet->id;
        $this->code = $packet->code;
        $this->label = (string) $packet->label;
        $this->box_id = $packet->box_id;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->code = strtoupper(trim($this->code));
        $this->box_id = $this->box_id ?: null;
        $data = $this->validate();

        if ($this->editingId) {
            Packet::findOrFail($this->editingId)->update($data);
            $message = "Packet {$data['code']} updated.";
        } else {
            Packet::create($data);
            $message = "Packet {$data['code']} created.";
        }

        $this->showForm = false;
        $this->reset(['editingId', 'code', 'label', 'box_id']);
        $this->dispatch('toast', message: $message, type: 'success');
    }

    public function openMove(): void
    {
        $this->moveToBoxId = null;
        $this->showMove = true;
    }

    // Saved one by one (not a mass update) so each reassignment lands in the packet's history.
    public function moveSelected(): void
    {
        $this->validate(['moveToBoxId' => 'nullable|exists:boxes,id'], [], ['moveToBoxId' => 'box']);

        $packets = Packet::whereIn('id', $this->selected)->get();
        foreach ($packets as $packet) {
            $packet->update(['box_id' => $this->moveToBoxId ?: null]);
        }

        $target = $this->moveToBoxId ? 'box ' . Box::find($this->moveToBoxId)->code : 'no box';
        $this->showMove = false;
        $this->selected = [];
        $this->dispatch('toast', message: "{$packets->count()} packet(s) moved to {$target}.", type: 'success');
    }

    public function printQr(int $id)
    {
        $qr = QrCode::forTarget('packet', Packet::findOrFail($id)->id);

        return $this->redirectRoute('stock.qr.print', ['ids' => $qr->id]);
    }

    public function printSelectedQr()
    {
        $ids = Packet::whereIn('id', $this->selected)->pluck('id')
            ->map(fn ($id) => QrCode::forTarget('packet', $id)->id);

        if ($ids->isEmpty()) {
            return;
        }

        return $this->redirectRoute('stock.qr.print', ['ids' => $ids->implode(',')]);
    }

    public function render()
    {
        $query = Packet::query()
            ->select('packets.*')
            ->leftJoin('boxes', 'boxes.id', '=', 'packets.box_id')
            ->addSelect('boxes.code as box_code', 'boxes.label as box_label')
            ->withCount('items')
            ->withSum('items', 'weight')
            ->withExists('qrCodes')
            ->when($this->search, fn ($q) => $q->where(fn ($q) => $q
                ->where('packets.code', 'like', "%{$this->search}%")
                ->orWhere('packets.label', 'like', "%{$this->search}%")))
            ->when($this->boxFilter === 'none', fn ($q) => $q->whereNull('packets.box_id'))
            ->when(ctype_digit($this->boxFilter), fn ($q) => $q->where('packets.box_id', (int) $this->boxFilter))
            ->when($this->contents === 'empty', fn ($q) => $q->doesntHave('items'))
            ->when($this->contents === 'filled', fn ($q) => $q->has('items'));

        $sortColumn = $this->sortableColumns()[$this->currentSortField()];
        $query->orderBy($sortColumn === 'code' ? 'packets.code' : $sortColumn, $this->currentSortDirection());

        return view('livewire.stock.packet-manager', [
            'packets' => $query->paginate($this->perPageValue()),
            'boxes' => Box::orderBy('code')->get(['id', 'code', 'label']),
            'stats' => [
                'packets' => Packet::count(),
                'items' => Item::whereNotNull('packet_id')->count(),
                'loose' => Packet::whereNull('box_id')->count(),
                'empty' => Packet::doesntHave('items')->count(),
            ],
        ])->layout('components.layouts.app', ['title' => 'Packets · Radharani Jewellery ERP']);
    }
}
