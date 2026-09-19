<?php
namespace App\Livewire\Stock;

use App\Models\Stock\Box;
use Livewire\Component;
use Livewire\WithPagination;

class BoxManager extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $editingId = null;
    public string $code = '';
    public string $label = '';

    protected $rules = [
        'code' => 'required|string|max:50|unique:boxes,code',
        'label' => 'nullable|string|max:100',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function edit(int $id)
    {
        $box = Box::findOrFail($id);
        $this->editingId = $box->id;
        $this->code = $box->code;
        $this->label = $box->label;
    }

    public function save()
    {
        $rules = $this->rules;
        if ($this->editingId) {
            $rules['code'] = 'required|string|max:50|unique:boxes,code,' . $this->editingId;
        }
        $this->validate($rules);

        Box::updateOrCreate(
            ['id' => $this->editingId],
            ['code' => $this->code, 'label' => $this->label]
        );

        $this->reset(['editingId', 'code', 'label']);
        session()->flash('message', 'Box saved.');
    }

    public function cancel()
    {
        $this->reset(['editingId', 'code', 'label']);
    }

    // No delete — boxes with movement history should never disappear.
    // Deactivation, not deletion, if that's ever needed later.

    public function render()
    {
        return view('livewire.stock.box-manager', [
            'boxes' => Box::where('code', 'like', "%{$this->search}%")
                ->orWhere('label', 'like', "%{$this->search}%")
                ->withCount('packets')
                ->orderByDesc('id')
                ->paginate(15),
        ]);
    }
}
