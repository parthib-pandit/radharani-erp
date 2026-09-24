<?php
namespace App\Livewire\Exchange;

use App\Models\Exchange\RefineryBatch;
use App\Services\PhotoCompressionService;
use Livewire\Component;
use Livewire\WithFileUploads;

class RefineryBatchSend extends Component
{
    use WithFileUploads;

    public float $weight = 0;
    public $photo = null;
    public ?string $result = null;

    public function submit()
    {
        $this->validate([
            'weight' => 'required|numeric|min:0.001',
            'photo' => 'required|image|max:5120',
        ]);

        $path = app(PhotoCompressionService::class)->store($this->photo, 'refinery');

        $batch = RefineryBatch::create([
            'weight' => $this->weight,
            'photo_path' => $path,
            'status' => 'sent',
            'sent_at' => now(),
            'created_by' => auth()->id(),
        ]);

        $this->result = "Batch #{$batch->id} of {$this->weight}g recorded for send.";
        $this->reset(['weight', 'photo']);
    }

    public function render()
    {
        return view('livewire.exchange.refinery-batch-send')->layout('components.layouts.app', ['title' => 'Refinery Batch — Send — Radharani Jewellery']);
    }
}
