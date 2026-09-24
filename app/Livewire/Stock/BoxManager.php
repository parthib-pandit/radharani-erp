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

class BoxManager extends Component
{
    use WithDataTable;

    #[Url(except: '')]
    public string $contents = ''; // '' | empty | filled

    // Add / edit modal
    public bool $showForm = false;
    public ?int $editingId = null;
    public string $code = '';
    public string $label = '';

    protected function sortableColumns(): array
    {
        return [
            'code' => 'code',
            'label' => 'label',
            'packets' => 'packets_count',
            'items' => 'items_count',
            'weight' => 'items_sum_weight',
            'created' => 'created_at',
        ];
    }

    protected function defaultSort(): array
    {
        return ['code', 'asc'];
    }

    protected function filterProperties(): array
    {
        return ['contents'];
    }

    protected function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50', Rule::unique('boxes', 'code')->ignore($this->editingId)],
            'label' => ['nullable', 'string', 'max:100'],
        ];
    }

    protected $validationAttributes = ['code' => 'box code'];

    public function create(): void
    {
        $this->resetValidation();
        $this->reset(['editingId', 'label']);
        $this->code = StockCodes::next(Box::class, 'BOX-');
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $this->resetValidation();
        $box = Box::findOrFail($id);
        $this->editingId = $box->id;
        $this->code = $box->code;
        $this->label = (string) $box->label;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->code = strtoupper(trim($this->code));
        $data = $this->validate();

        if ($this->editingId) {
            Box::findOrFail($this->editingId)->update($data);
            $message = "Box {$data['code']} updated.";
        } else {
            Box::create($data);
            $message = "Box {$data['code']} created.";
        }

        $this->showForm = false;
        $this->reset(['editingId', 'code', 'label']);
        $this->dispatch('toast', message: $message, type: 'success');
    }

    // No delete: boxes with movement history should never disappear.

    // Mint (or reuse) the box's QR sticker and open the print sheet.
    public function printQr(int $id)
    {
        $qr = QrCode::forTarget('box', Box::findOrFail($id)->id);

        return $this->redirectRoute('stock.qr.print', ['ids' => $qr->id]);
    }

    public function printSelectedQr()
    {
        $ids = Box::whereIn('id', $this->selected)->pluck('id')
            ->map(fn ($id) => QrCode::forTarget('box', $id)->id);

        if ($ids->isEmpty()) {
            return;
        }

        return $this->redirectRoute('stock.qr.print', ['ids' => $ids->implode(',')]);
    }

    public function render()
    {
        $query = Box::query()
            ->withCount(['packets', 'items'])
            ->withSum('items', 'weight')
            ->withExists('qrCodes')
            ->when($this->search, fn ($q) => $q->where(fn ($q) => $q
                ->where('code', 'like', "%{$this->search}%")
                ->orWhere('label', 'like', "%{$this->search}%")))
            ->when($this->contents === 'empty', fn ($q) => $q->doesntHave('packets'))
            ->when($this->contents === 'filled', fn ($q) => $q->has('packets'));

        return view('livewire.stock.box-manager', [
            'boxes' => $this->applySorting($query)->paginate($this->perPageValue()),
            'stats' => [
                'boxes' => Box::count(),
                'packets' => Packet::whereNotNull('box_id')->count(),
                'items' => Item::whereHas('packet', fn ($q) => $q->whereNotNull('box_id'))->count(),
                'loosePackets' => Packet::whereNull('box_id')->count(),
            ],
        ])->layout('components.layouts.app', ['title' => 'Boxes · Radharani Jewellery ERP']);
    }
}
