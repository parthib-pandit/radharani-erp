<?php
namespace App\Livewire\Pricing;

use Livewire\Component;

/**
 * Making-Charge Configuration — category-level presets.
 *
 * Two gaps here, both already flagged elsewhere in this build:
 * 1. items.making_type only has an enum of (per_piece, percentage) — the
 *    spec's third type, a flat amount per gram, has nowhere to be stored.
 * 2. There's no table for category-level making-charge PRESETS at all —
 *    items.making_type/making_value are set per item (Stock > Add/Edit
 *    Item already covers that). This screen is the "set a default so
 *    staff don't have to type it every time" idea, which needs its own
 *    small table. Frontend only, sample presets, for both reasons.
 */
class MakingChargeConfig extends Component
{
    public string $selectedType = 'percentage'; // percentage | per_piece | per_gram
    public string $category = '';
    public float $value = 0;
    public ?string $result = null;

    public function save()
    {
        $this->validate(['category' => 'required|string|max:50', 'value' => 'required|numeric|min:0']);
        $this->result = "Preset captured for \"{$this->category}\" — will save for real once a making_charge_presets table exists (and the per_gram type is added to items.making_type).";
    }

    public function render()
    {
        $sample = [
            ['category' => 'Gold Chains', 'type' => 'percentage', 'value' => 12],
            ['category' => 'Silver Idols', 'type' => 'per_gram', 'value' => 45],
            ['category' => 'Earrings', 'type' => 'per_piece', 'value' => 350],
        ];

        return view('livewire.pricing.making-charge-config', ['presets' => $sample])
            ->layout('components.layouts.app', ['title' => 'Making-Charge Configuration — Radharani Jewellery']);
    }
}
