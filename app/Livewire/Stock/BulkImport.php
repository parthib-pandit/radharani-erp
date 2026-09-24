<?php
namespace App\Livewire\Stock;

use App\Models\Stock\Item;
use Livewire\Component;
use Livewire\WithFileUploads;

class BulkImport extends Component
{
    use WithFileUploads;

    public int $step = 1;

    // Step 1
    public $file = null;
    public array $headers = [];
    public array $rows = [];

    // Step 2 — spreadsheet header => system field
    public array $mapping = [];
    public array $systemFields = [
        'huid_code' => 'HUID Code',
        'category' => 'Category',
        'purity' => 'Purity',
        'weight' => 'Weight (g)',
        'hsn_code' => 'HSN Code',
        'description' => 'Description',
    ];

    // Step 3
    public array $reviewRows = [];
    public int $importedCount = 0;

    public function uploadFile()
    {
        $this->validate(['file' => 'required|file|mimes:csv,txt|max:5120']);

        $handle = fopen($this->file->getRealPath(), 'r');
        $this->headers = fgetcsv($handle) ?: [];
        $this->rows = [];
        while (($row = fgetcsv($handle)) !== false && count($this->rows) < 500) {
            $this->rows[] = $row;
        }
        fclose($handle);

        // Best-guess auto-mapping by header name.
        foreach ($this->headers as $h) {
            $key = strtolower(trim($h));
            foreach ($this->systemFields as $field => $label) {
                if (str_contains($key, str_replace('_', ' ', $field)) || str_contains($key, $field)) {
                    $this->mapping[$h] = $field;
                }
            }
        }

        $this->step = 2;
    }

    public function confirmMapping()
    {
        $this->reviewRows = [];

        foreach ($this->rows as $row) {
            $record = [];
            foreach ($this->headers as $i => $h) {
                if (! empty($this->mapping[$h])) {
                    $record[$this->mapping[$h]] = $row[$i] ?? null;
                }
            }
            if (empty($record)) continue;

            $isDuplicate = ! empty($record['huid_code']) && Item::where('huid_code', $record['huid_code'])->exists();

            $this->reviewRows[] = [
                'data' => $record,
                'duplicate' => $isDuplicate,
                'include' => ! $isDuplicate,
            ];
        }

        $this->step = 3;
    }

    public function toggleInclude(int $index)
    {
        $this->reviewRows[$index]['include'] = ! $this->reviewRows[$index]['include'];
    }

    public function confirmImport()
    {
        $count = 0;
        foreach ($this->reviewRows as $row) {
            if (! $row['include']) continue;

            Item::create([
                'huid_code' => $row['data']['huid_code'] ?? null,
                'category' => $row['data']['category'] ?? 'Uncategorised',
                'purity' => $row['data']['purity'] ?? '',
                'weight' => is_numeric($row['data']['weight'] ?? null) ? $row['data']['weight'] : 0,
                'hsn_code' => $row['data']['hsn_code'] ?? null,
                'description' => $row['data']['description'] ?? null,
                'making_type' => 'per_piece',
                'making_value' => 0,
            ]);
            $count++;
        }

        $this->importedCount = $count;
        $this->step = 4;
    }

    public function startOver()
    {
        $this->reset(['step', 'file', 'headers', 'rows', 'mapping', 'reviewRows', 'importedCount']);
    }

    public function render()
    {
        return view('livewire.stock.bulk-import')
            ->layout('components.layouts.app', ['title' => 'Bulk Import — Radharani Jewellery']);
    }
}
