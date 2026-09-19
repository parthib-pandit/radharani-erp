<?php
namespace App\Livewire\Stock;

use App\Models\Stock\Box;
use App\Models\Stock\Packet;
use Livewire\Component;
use Livewire\WithPagination;

class PacketManager extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $editingId = null;
    public string $code = '';
    public string $label = '';
    public ?int $box_id = null;

    protected $rules = [
        'code' => 'required|string|max:50|unique:packets,code',
        'label' => 'nullable|string|max:100',
        'box_id' => 'nullable|exists:boxes,id',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function edit(int $id)
    {
        $packet = Packet::findOrFail($id);
        $this->editingId = $packet->id;
        $this->code = $packet->code;
        $this->label = $packet->label;
        $this->box_id = $packet->box_id;
    }

    public function save()
    {
        $rules = $this->rules;
        if ($this->editingId) {
            $rules['code'] = 'required|string|max:50|unique:packets,code,' . $this->editingId;
        }
        $this->validate($rules);

        Packet::updateOrCreate(
            ['id' => $this->editingId],
            ['code' => $this->code, 'label' => $this->label, 'box_id' => $this->box_id]
        );

        $this->reset(['editingId', 'code', 'label', 'box_id']);
        session()->flash('message', 'Packet saved.');
    }

    public function cancel()
    {
        $this->reset(['editingId', 'code', 'label', 'box_id']);
    }

    public function render()
    {
        return view('livewire.stock.packet-manager', [
            'packets' => Packet::with('box')
                ->where('code', 'like', "%{$this->search}%")
                ->withCount('items')
                ->orderByDesc('id')
                ->paginate(15),
            'boxes' => Box::orderBy('code')->get(),
        ]);
    }
}
