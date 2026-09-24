<?php
namespace App\Livewire\Stock;

use App\Models\Stock\Box;
use App\Models\Stock\Item;
use App\Models\Stock\Packet;
use App\Models\Stock\QrCode;
use Illuminate\Support\Str;
use Livewire\Component;

class QrGenerator extends Component
{
    public string $mode = 'single'; // single | batch

    // Single mode — staff, immediate print
    public string $singleTargetType = 'item';
    public string $singleTargetCode = '';
    public ?array $singleResult = null;
    public ?string $singleError = null;

    // Batch mode — admin, several ahead of time
    public string $batchTargetType = 'item';
    public string $batchStatusFilter = 'in_stock';
    public int $batchCount = 10;
    public array $batchResults = [];

    public function generateSingle()
    {
        $this->singleResult = null;
        $this->singleError = null;

        $target = match ($this->singleTargetType) {
            'item' => Item::where('huid_code', $this->singleTargetCode)->orWhere('internal_code', $this->singleTargetCode)->first(),
            'packet' => Packet::where('code', $this->singleTargetCode)->first(),
            'box' => Box::where('code', $this->singleTargetCode)->first(),
        };

        if (! $target) {
            $this->singleError = "No {$this->singleTargetType} found for \"{$this->singleTargetCode}\".";
            return;
        }

        $code = strtoupper(Str::random(8));
        QrCode::create(['target_type' => $this->singleTargetType, 'target_id' => $target->id, 'code' => $code]);

        $this->singleResult = [
            'code' => $code,
            'label' => $target->huid_code ?? $target->internal_code ?? $target->code,
        ];
    }

    public function generateBatch()
    {
        $query = match ($this->batchTargetType) {
            'item' => Item::when($this->batchStatusFilter, fn ($q) => $q->where('status', $this->batchStatusFilter)),
            'packet' => Packet::query(),
            'box' => Box::query(),
        };

        $targets = $query->latest('id')->limit($this->batchCount)->get();
        $results = [];

        foreach ($targets as $target) {
            $code = strtoupper(Str::random(8));
            QrCode::create(['target_type' => $this->batchTargetType, 'target_id' => $target->id, 'code' => $code]);
            $results[] = [
                'code' => $code,
                'label' => $target->huid_code ?? $target->internal_code ?? $target->code,
            ];
        }

        $this->batchResults = $results;
    }

    public function render()
    {
        return view('livewire.stock.qr-generator')
            ->layout('components.layouts.app', ['title' => 'QR Codes — Radharani Jewellery']);
    }
}
