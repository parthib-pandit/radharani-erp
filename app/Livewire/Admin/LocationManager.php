<?php
namespace App\Livewire\Admin;

use App\Models\Location;
use Illuminate\Validation\Rule;
use Livewire\Component;

/** Owner-managed list of in-store locations (8 Oct change list, 3.1). */
class LocationManager extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;
    public string $name = '';
    public string $type = 'counter';
    public int $sort_order = 0;
    public ?int $parent_id = null; // a tray's vault

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:60', Rule::unique('locations', 'name')->ignore($this->editingId)],
            'type' => ['required', Rule::in(array_keys(Location::TYPES))],
            'sort_order' => ['integer', 'min:0', 'max:999'],
        ];
    }

    public function create(): void
    {
        $this->resetValidation();
        $this->reset(['editingId', 'name', 'parent_id']);
        $this->type = 'counter';
        $this->sort_order = (int) Location::max('sort_order') + 1;
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $this->resetValidation();
        $l = Location::findOrFail($id);
        $this->editingId = $l->id;
        $this->name = $l->name;
        $this->type = $l->type;
        $this->sort_order = $l->sort_order;
        $this->parent_id = $l->type === 'tray' ? $l->parent_id : null;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->name = trim($this->name);
        $data = $this->validate();

        $existing = $this->editingId ? Location::findOrFail($this->editingId) : null;

        // A tray sits in one of the vaults (the first one unless another is chosen); nothing else has a parent.
        if ($data['type'] === 'tray') {
            $vault = $this->parent_id ? Location::where('type', 'vault')->find($this->parent_id) : Location::vault();
            if (! $vault) {
                $this->addError('type', 'Add a vault first. A tray is a place inside one.');

                return;
            }
            $data['parent_id'] = $vault->id;
        } else {
            $data['parent_id'] = null;
            // A place with trays in it stays a vault.
            if ($existing && Location::where('parent_id', $existing->id)->exists()) {
                $this->addError('type', 'This place has trays in it, so it has to stay a vault.');

                return;
            }
        }

        // A vault can stop being a vault only if the shop keeps another one and nothing is kept in it.
        if ($existing && $existing->type === 'vault' && $data['type'] !== 'vault') {
            if (Location::where('type', 'vault')->where('id', '!=', $existing->id)->doesntExist()) {
                $this->addError('type', 'The shop needs at least one vault.');

                return;
            }
            if ($existing->keptCount() > 0) {
                $this->addError('type', "Boxes are kept in {$existing->name}. Move them to another vault first.");

                return;
            }
        }

        $this->editingId ? Location::findOrFail($this->editingId)->update($data) : Location::create($data);
        $this->showForm = false;
        $this->dispatch('toast', message: "{$data['name']} saved.", type: 'success');
    }

    public function toggle(int $id): void
    {
        $l = Location::findOrFail($id);
        if ($l->type === 'vault' && $l->is_active) {
            // Another vault has to stay on, nothing may be kept here, and its trays go off first.
            $error = match (true) {
                Location::where('type', 'vault')->where('is_active', true)->where('id', '!=', $l->id)->doesntExist() => 'The shop needs at least one vault switched on.',
                $l->keptCount() > 0 => "Boxes are kept in {$l->name}. Move them to another vault first.",
                Location::where('parent_id', $l->id)->where('is_active', true)->exists() => "{$l->name} still has trays switched on. Switch them off first.",
                default => null,
            };
            if ($error) {
                $this->dispatch('toast', message: $error, type: 'error');

                return;
            }
        }
        $l->update(['is_active' => ! $l->is_active]);
        $this->dispatch('toast', message: $l->name . ($l->is_active ? ' is on.' : ' is switched off.'), type: 'success');
    }

    public function render()
    {
        return view('livewire.admin.location-manager', [
            'locations' => Location::withCount('movements')->orderBy('sort_order')->orderBy('id')->get(),
            'vaults' => Location::where('type', 'vault')->where('is_active', true)->orderBy('sort_order')->orderBy('id')->get(['id', 'name']),
            'types' => Location::TYPES,
        ])->layout('components.layouts.app', ['title' => 'Locations · Radharani Jewellery']);
    }
}
