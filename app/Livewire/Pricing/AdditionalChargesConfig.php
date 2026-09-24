<?php
namespace App\Livewire\Pricing;

use Livewire\Component;

/**
 * Additional Charges Config — named preset charges (Sakha, Pola, etc).
 *
 * `sales.additional_charges` is a JSON column on each sale — there's no
 * separate presets table to manage a reusable named list from. Frontend
 * only, in-memory list, so this is still fully editable and evaluable.
 */
class AdditionalChargesConfig extends Component
{
    public array $charges = [
        ['name' => 'Sakha', 'value' => 150],
        ['name' => 'Pola', 'value' => 120],
        ['name' => 'Batana', 'value' => 80],
        ['name' => 'Tarsel', 'value' => 60],
    ];

    public string $newName = '';
    public float $newValue = 0;

    public function updateValue(int $index, $value)
    {
        $this->charges[$index]['value'] = (float) $value;
    }

    public function remove(int $index)
    {
        unset($this->charges[$index]);
        $this->charges = array_values($this->charges);
    }

    public function add()
    {
        $this->validate(['newName' => 'required|string|max:50', 'newValue' => 'required|numeric|min:0']);
        $this->charges[] = ['name' => $this->newName, 'value' => $this->newValue];
        $this->reset(['newName', 'newValue']);
    }

    public function render()
    {
        return view('livewire.pricing.additional-charges-config')
            ->layout('components.layouts.app', ['title' => 'Additional Charges — Radharani Jewellery']);
    }
}
