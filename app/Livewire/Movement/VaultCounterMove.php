<?php
namespace App\Livewire\Movement;

use App\Models\Movement\Movement;
use App\Models\Stock\Box;
use App\Models\Stock\Item;
use App\Models\Stock\Packet;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class VaultCounterMove extends Component
{
    public string $direction = 'to_counter'; // to_counter | to_vault
    public string $targetType = 'item';
    public string $code = '';
    public ?string $result = null;
    public ?string $error = null;

    public function setDirection(string $direction)
    {
        $this->direction = $direction;
        $this->reset(['result', 'error']);
    }

    public function submit()
    {
        $this->result = null;
        $this->error = null;

        $target = match ($this->targetType) {
            'item' => Item::where('huid_code', $this->code)->orWhere('internal_code', $this->code)->first(),
            'packet' => Packet::where('code', $this->code)->first(),
            'box' => Box::where('code', $this->code)->first(),
        };

        if (! $target) {
            $this->error = "No {$this->targetType} found for \"{$this->code}\".";
            return;
        }

        Movement::create([
            'trackable_type' => $this->targetType,
            'trackable_id' => $target->id,
            'movement_type' => $this->direction === 'to_counter' ? 'vault_out' : 'vault_in',
            'purpose_label' => $this->direction === 'to_counter' ? 'Counter display' : null,
            'user_id' => Auth::id(),
            'weight_at_dispatch' => $target->weight ?? null,
        ]);

        $label = $target->huid_code ?? $target->internal_code ?? $target->code;
        $this->result = "{$label} " . ($this->direction === 'to_counter' ? 'sent to counter.' : 'returned to vault.');
        $this->code = '';
    }

    public function render()
    {
        return view('livewire.movement.vault-counter-move', [
            'today' => Movement::whereIn('movement_type', ['vault_out', 'vault_in'])
                ->whereDate('created_at', today())
                ->with('user')
                ->orderByDesc('created_at')
                ->limit(20)
                ->get(),
        ])->layout('components.layouts.app', ['title' => 'Vault ↔ Counter — Radharani Jewellery']);
    }
}
