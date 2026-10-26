<?php
namespace App\Livewire\Stock;

use App\Livewire\Concerns\WithDataTable;
use App\Models\Location;
use App\Models\Stock\Box;
use App\Models\Stock\Packet;
use App\Support\StockCodes;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * Boxes and packets in one list with a type column (8 Oct change list, 4.1).
 * Empty ones can be deleted; that is a soft delete, so the row and its audit
 * trail stay but it disappears from every list.
 */
class ContainerList extends Component
{
    use WithDataTable;

    #[Url(except: '')]
    public string $type = ''; // '' | box | packet

    #[Url(except: '')]
    public string $contents = ''; // '' | empty | filled

    public bool $showForm = false;
    public string $formType = 'box';
    public ?int $editingId = null;
    public string $code = '';
    public string $label = '';
    public ?int $box_id = null;
    public ?int $home_vault_id = null; // the vault a box, or a packet not in a box, is kept in
    public string $suggestedCode = '';

    public function mount(): void
    {
        // The old /stock/packets address opens this list on the packets filter.
        if (request()->routeIs('stock.packets') && $this->type === '') {
            $this->type = 'packet';
        }
    }

    protected function sortableColumns(): array
    {
        return [
            'code' => 'code',
            'type' => 'kind',
            'parent' => 'parent_code',
            'items' => 'items_count',
            'weight' => 'weight',
            'created' => 'created_at',
        ];
    }

    protected function defaultSort(): array
    {
        return ['code', 'asc'];
    }

    protected function filterProperties(): array
    {
        return ['type', 'contents'];
    }

    protected function rules(): array
    {
        $table = $this->formType === 'box' ? 'boxes' : 'packets';

        return [
            'code' => ['required', 'string', 'max:50', Rule::unique($table, 'code')->ignore($this->editingId)],
            'label' => ['nullable', 'string', 'max:100'],
            'box_id' => ['nullable', 'exists:boxes,id,deleted_at,NULL'],
            'home_vault_id' => ['nullable', Rule::exists('locations', 'id')->where('type', 'vault')],
        ];
    }

    protected $validationAttributes = ['code' => 'code', 'box_id' => 'box'];

    public function create(string $type): void
    {
        $this->resetValidation();
        $this->reset(['editingId', 'label', 'box_id', 'home_vault_id']);
        $this->formType = $type === 'packet' ? 'packet' : 'box';
        $this->code = $this->suggestedCode = $this->suggestCode();
        $this->showForm = true;
    }

    public function updatedBoxId(): void
    {
        if (! $this->editingId && $this->formType === 'packet' && $this->code === $this->suggestedCode) {
            $this->code = $this->suggestedCode = $this->suggestCode();
        }
    }

    private function suggestCode(): string
    {
        return $this->formType === 'box'
            ? StockCodes::next(Box::class, 'BOX-')
            : StockCodes::next(Packet::class, $this->box_id ? "PKT-{$this->box_id}-" : 'PKT-');
    }

    public function edit(string $type, int $id): void
    {
        $this->resetValidation();
        $this->formType = $type === 'packet' ? 'packet' : 'box';
        $model = $this->formType === 'box' ? Box::findOrFail($id) : Packet::findOrFail($id);
        $this->editingId = $model->id;
        $this->code = $model->code;
        $this->label = (string) $model->label;
        $this->box_id = $this->formType === 'packet' ? $model->box_id : null;
        $this->home_vault_id = $model->home_vault_id;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->code = strtoupper(trim($this->code));
        $this->box_id = $this->box_id ?: null;
        $this->home_vault_id = $this->home_vault_id ?: null;
        $data = $this->validate();
        // A packet in a box is kept where its box is kept.
        if ($this->formType === 'packet' && $this->box_id) {
            $data['home_vault_id'] = null;
        }
        $word = $this->formType === 'box' ? 'Box' : 'Packet';

        if ($this->formType === 'box') {
            unset($data['box_id']);
            $this->editingId ? Box::findOrFail($this->editingId)->update($data) : Box::create($data);
        } else {
            $this->editingId ? Packet::findOrFail($this->editingId)->update($data) : Packet::create($data);
        }

        $message = "{$word} {$data['code']} " . ($this->editingId ? 'updated.' : 'created.');
        $this->showForm = false;
        $this->reset(['editingId', 'code', 'label', 'box_id', 'home_vault_id']);
        $this->dispatch('toast', message: $message, type: 'success');
    }

    // Soft delete, empty containers only.
    public function deleteContainer(string $type, int $id): void
    {
        $model = $type === 'packet' ? Packet::find($id) : Box::find($id);
        if (! $model) {
            return;
        }

        $inside = $type === 'packet' ? $model->items()->count() : $model->packets()->count();
        if ($inside > 0) {
            $this->dispatch('toast', message: "{$model->code} is not empty, so it can't be deleted.", type: 'error');

            return;
        }

        $model->delete();
        activity('stock')->performedOn($model)->causedBy(auth()->user())->event('deleted')
            ->log(($type === 'packet' ? 'Packet' : 'Box') . " {$model->code} deleted (empty)");

        $this->selected = [];
        $this->dispatch('toast', message: "{$model->code} deleted. It stays in the audit trail.", type: 'success');
    }

    private function union()
    {
        $boxes = DB::table('boxes')->whereNull('boxes.deleted_at')->selectRaw("
            'box' as kind, boxes.id, boxes.code, boxes.label, NULL as parent_id, NULL as parent_code, boxes.home_vault_id as vault_id,
            (select count(*) from packets p where p.box_id = boxes.id and p.deleted_at is null) as child_count,
            (select count(*) from items i join packets p on p.id = i.packet_id where p.box_id = boxes.id and i.deleted_at is null and p.deleted_at is null and i.status <> 'sold') as items_count,
            (select count(*) from items i join packets p on p.id = i.packet_id where p.box_id = boxes.id and i.deleted_at is null and p.deleted_at is null and i.status = 'sold') as sold_count,
            (select coalesce(sum(i.weight), 0) from items i join packets p on p.id = i.packet_id where p.box_id = boxes.id and i.deleted_at is null and p.deleted_at is null and i.status <> 'sold') as weight,
            boxes.created_at,
            (select max(a.created_at) from stock_audits a where a.box_id = boxes.id) as last_audit");

        $packets = DB::table('packets')->leftJoin('boxes as b', fn ($j) => $j->on('b.id', '=', 'packets.box_id')->whereNull('b.deleted_at'))
            ->whereNull('packets.deleted_at')->selectRaw("
            'packet' as kind, packets.id, packets.code, packets.label, b.id as parent_id, b.code as parent_code, case when b.id is not null then b.home_vault_id else packets.home_vault_id end as vault_id,
            0 as child_count,
            (select count(*) from items i where i.packet_id = packets.id and i.deleted_at is null and i.status <> 'sold') as items_count,
            (select count(*) from items i where i.packet_id = packets.id and i.deleted_at is null and i.status = 'sold') as sold_count,
            (select coalesce(sum(i.weight), 0) from items i where i.packet_id = packets.id and i.deleted_at is null and i.status <> 'sold') as weight,
            packets.created_at,
            NULL as last_audit");

        return DB::query()->fromSub($boxes->unionAll($packets), 'c');
    }

    public function render()
    {
        $query = $this->union()
            ->when($this->type !== '', fn ($q) => $q->where('kind', $this->type === 'packet' ? 'packet' : 'box'))
            ->when($this->search, fn ($q) => $q->where(fn ($q) => $q
                ->where('code', 'like', "%{$this->search}%")
                ->orWhere('label', 'like', "%{$this->search}%")))
            ->when($this->contents === 'empty', fn ($q) => $q->where('items_count', 0)->where('sold_count', 0)->where('child_count', 0))
            ->when($this->contents === 'filled', fn ($q) => $q->where(fn ($q) => $q->where('items_count', '>', 0)->orWhere('sold_count', '>', 0)->orWhere('child_count', '>', 0)));

        $query->orderBy($this->sortableColumns()[$this->currentSortField()], $this->currentSortDirection())->orderBy('kind')->orderBy('id');

        return view('livewire.stock.container-list', [
            'rows' => $query->paginate($this->perPageValue()),
            'boxes' => Box::orderBy('code')->get(['id', 'code', 'label']),
            'vaults' => Location::where('type', 'vault')->where('is_active', true)->orderBy('sort_order')->orderBy('id')->get(['id', 'name']),
            'vaultNames' => Location::where('type', 'vault')->pluck('name', 'id'),
            'defaultVaultId' => Location::vault()?->id,
            'stats' => [
                'boxes' => Box::count(),
                'packets' => Packet::count(),
                'loosePackets' => Packet::whereNull('box_id')->count(),
                'empty' => $this->union()->where('items_count', 0)->where('sold_count', 0)->where('child_count', 0)->count(),
            ],
        ])->layout('components.layouts.app', ['title' => 'Boxes & Packets · Radharani Jewellery ERP']);
    }
}
