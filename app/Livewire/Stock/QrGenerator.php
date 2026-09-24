<?php
namespace App\Livewire\Stock;

use App\Livewire\Concerns\WithDataTable;
use App\Models\Stock\Box;
use App\Models\Stock\Item;
use App\Models\Stock\Packet;
use App\Models\Stock\QrCode;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * QR sticker tool. Two open paths, no approval gate between them (Requirement #3):
 *  - single: staff issue one label on the spot while handling stock
 *  - batch:  admin issues many ahead of time and prints a sheet
 * One sticker per target: an existing code is reused rather than replaced,
 * so labels already stuck on packets never go stale.
 */
class QrGenerator extends Component
{
    use WithDataTable;

    #[Url(except: 'single')]
    public string $mode = 'single'; // single | batch

    // Single
    public string $singleType = 'packet';
    public string $singleCode = '';
    public ?int $singleQrId = null;
    public ?string $singleError = null;

    // Batch
    public string $batchType = 'packet';
    public bool $batchOnlyMissing = true;
    public string $batchBox = '';
    public string $batchStatus = 'in_stock';
    public int $batchLimit = 50;

    // Register filter
    #[Url(as: 'type', except: '')]
    public string $typeFilter = '';

    protected function sortableColumns(): array
    {
        return ['issued' => 'id', 'code' => 'code', 'type' => 'target_type'];
    }

    protected function defaultSort(): array
    {
        return ['issued', 'desc'];
    }

    protected function filterProperties(): array
    {
        return ['typeFilter'];
    }

    // ------------------------------------------------------------- single

    public function updatedSingleType(): void
    {
        $this->reset(['singleCode', 'singleQrId', 'singleError']);
    }

    public function pickSuggestion(string $code): void
    {
        $this->singleCode = $code;
        $this->generateSingle();
    }

    public function generateSingle(): void
    {
        $this->singleError = null;
        $this->singleQrId = null;
        $code = strtoupper(trim($this->singleCode));

        $target = match ($this->singleType) {
            'item' => Item::where('huid_code', $code)->orWhere('internal_code', $code)->first(),
            'packet' => Packet::where('code', $code)->first(),
            'box' => Box::where('code', $code)->first(),
        };

        if (! $target) {
            $this->singleError = 'No ' . ($this->singleType === 'item' ? 'piece' : $this->singleType) . " has the code \"{$code}\".";
            return;
        }

        $existed = QrCode::where('target_type', $this->singleType)->where('target_id', $target->id)->exists();
        $this->singleQrId = QrCode::forTarget($this->singleType, $target->id)->id;
        $this->dispatch('toast', message: $existed ? 'This one already had a label. Showing it for reprinting.' : 'New QR label issued.', type: $existed ? 'info' : 'success');
    }

    // -------------------------------------------------------------- batch

    private function batchQuery(): Builder
    {
        $query = match ($this->batchType) {
            'item' => Item::query()
                ->when($this->batchStatus, fn ($q) => $q->where('status', $this->batchStatus))
                ->when(ctype_digit($this->batchBox), fn ($q) => $q->whereHas('packet', fn ($p) => $p->where('box_id', (int) $this->batchBox))),
            'packet' => Packet::query()
                ->when(ctype_digit($this->batchBox), fn ($q) => $q->where('box_id', (int) $this->batchBox)),
            'box' => Box::query(),
        };

        if ($this->batchOnlyMissing) {
            $query->whereNotExists(fn ($q) => $q->selectRaw(1)->from('qr_codes')
                ->where('qr_codes.target_type', $this->batchType)
                ->whereColumn('qr_codes.target_id', $query->getModel()->getTable() . '.id'));
        }

        return $query;
    }

    public function generateBatch()
    {
        $this->validate(['batchLimit' => 'required|integer|min:1|max:300'], [], ['batchLimit' => 'how many']);

        $targets = $this->batchQuery()->orderBy($this->batchType === 'item' ? 'id' : 'code')->limit($this->batchLimit)->pluck('id');

        if ($targets->isEmpty()) {
            $this->dispatch('toast', message: 'Nothing matches. Every one of these already has a label.', type: 'warning');
            return null;
        }

        $ids = $targets->map(fn ($id) => QrCode::forTarget($this->batchType, $id)->id);

        return $this->redirectRoute('stock.qr.print', ['ids' => $ids->implode(',')]);
    }

    // ----------------------------------------------------------- register

    public function printSelected()
    {
        $ids = QrCode::whereIn('id', $this->selected)->pluck('id');

        return $ids->isEmpty() ? null : $this->redirectRoute('stock.qr.print', ['ids' => $ids->implode(',')]);
    }

    public function render()
    {
        $codes = $this->applySorting(QrCode::query()
            ->when($this->typeFilter, fn ($q) => $q->where('target_type', $this->typeFilter))
            ->when($this->search, fn ($q) => $q->where('code', 'like', '%' . strtoupper($this->search) . '%')))
            ->paginate($this->perPageValue());

        // Resolve target labels for this page in three queries rather than one per row.
        $byType = $codes->getCollection()->groupBy('target_type');
        $targets = [
            'item' => Item::whereIn('id', ($byType['item'] ?? collect())->pluck('target_id'))->get()->keyBy('id'),
            'packet' => Packet::whereIn('id', ($byType['packet'] ?? collect())->pluck('target_id'))->get()->keyBy('id'),
            'box' => Box::whereIn('id', ($byType['box'] ?? collect())->pluck('target_id'))->get()->keyBy('id'),
        ];

        $suggestions = collect();
        if ($this->mode === 'single' && strlen(trim($this->singleCode)) >= 1 && ! $this->singleQrId) {
            $term = '%' . trim($this->singleCode) . '%';
            $suggestions = match ($this->singleType) {
                'item' => Item::where('huid_code', 'like', $term)->orWhere('internal_code', 'like', $term)->limit(6)->get()
                    ->map(fn ($i) => ['code' => $i->label, 'sub' => $i->category . ' · ' . number_format($i->weight, 3) . ' g']),
                'packet' => Packet::where('code', 'like', $term)->orWhere('label', 'like', $term)->limit(6)->get()
                    ->map(fn ($p) => ['code' => $p->code, 'sub' => $p->label ?: 'Packet']),
                'box' => Box::where('code', 'like', $term)->orWhere('label', 'like', $term)->limit(6)->get()
                    ->map(fn ($b) => ['code' => $b->code, 'sub' => $b->label ?: 'Box']),
            };
        }

        $singleQr = $this->singleQrId ? QrCode::find($this->singleQrId) : null;

        return view('livewire.stock.qr-generator', [
            'codes' => $codes,
            'targets' => $targets,
            'suggestions' => $suggestions,
            'singleQr' => $singleQr,
            'singleTarget' => $singleQr?->target(),
            'batchMatches' => $this->mode === 'batch' ? $this->batchQuery()->count() : 0,
            'boxes' => Box::orderBy('code')->get(['id', 'code', 'label']),
            'counts' => QrCode::selectRaw('target_type, count(*) as c')->groupBy('target_type')->pluck('c', 'target_type'),
        ])->layout('components.layouts.app', ['title' => 'QR Codes · Radharani Jewellery']);
    }
}
