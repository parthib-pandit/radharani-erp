<?php
namespace App\Livewire\Exchange;

use App\Models\Customer\Customer;
use App\Models\Exchange\ExchangeTransaction;
use Livewire\Component;

/**
 * Old Gold/Silver Exchange — New Entry.
 *
 * The client was explicit that no step in this 4-step process (gross
 * weight → net weight after melt → two independent purity readings,
 * auto-averaged → preset deduction) should be abstracted or combined, so
 * each step persists its own slice of the `exchange_transactions` row as
 * it's completed, rather than writing everything at the end:
 *   Step 1 → create the row (customer_id, gross_weight, description),
 *            stage = 'received'.
 *   Step 2 → update net_weight, stage = 'melted' (this is literally the
 *            "net weight after melt" step — judgment call: the doc only
 *            explicitly calls out 'received' and 'tested', but the
 *            'melted' enum value exists precisely for this step).
 *   Step 3 → update purity_test_1/2 + computed purity_averaged,
 *            stage = 'tested' once both readings are in.
 *   Step 4 → update preset_deduction_percent + computed deductable_weight.
 * `valued`/`settled` stages are set later by AccountsValuation, not here.
 */
class NewEntry extends Component
{
    public int $step = 1;

    public ?int $transactionId = null;

    public ?int $customerId = null;
    public string $customerSearch = '';
    public float $grossWeight = 0;
    public string $description = '';

    public float $netWeight = 0;

    public float $purityTest1 = 0;
    public float $purityTest2 = 0;

    public float $presetDeductionPercent = 2.0; // shop preset — shown, not typed

    public function goToStep(int $target)
    {
        // Guided form — never allow skipping ahead of what's been completed.
        if ($target <= $this->step + 1) {
            $this->step = min($target, 5);
        }
    }

    public function next()
    {
        if ($this->step === 1) {
            $this->validate([
                'customerId' => 'required|exists:customers,id',
                'grossWeight' => 'required|numeric|min:0.001',
            ]);

            $transaction = ExchangeTransaction::create([
                'customer_id' => $this->customerId,
                'gross_weight' => $this->grossWeight,
                'description' => $this->description ?: null,
                'stage' => 'received',
                'created_by' => auth()->id(),
            ]);

            $this->transactionId = $transaction->id;
        }

        if ($this->step === 2) {
            $this->validate(['netWeight' => 'required|numeric|min:0.001']);

            ExchangeTransaction::whereKey($this->transactionId)->update([
                'net_weight' => $this->netWeight,
                'stage' => 'melted',
            ]);
        }

        if ($this->step === 3) {
            $this->validate([
                'purityTest1' => 'required|numeric|min:0|max:100',
                'purityTest2' => 'required|numeric|min:0|max:100',
            ]);

            ExchangeTransaction::whereKey($this->transactionId)->update([
                'purity_test_1' => $this->purityTest1,
                'purity_test_2' => $this->purityTest2,
                'purity_averaged' => $this->averagePurity,
                'stage' => 'tested',
            ]);
        }

        if ($this->step === 4) {
            ExchangeTransaction::whereKey($this->transactionId)->update([
                'preset_deduction_percent' => $this->presetDeductionPercent,
                'deductable_weight' => $this->deductedWeight,
            ]);
        }

        $this->step = min($this->step + 1, 5);
    }

    public function back()
    {
        $this->step = max($this->step - 1, 1);
    }

    public function getAveragePurityProperty()
    {
        return $this->purityTest1 && $this->purityTest2
            ? round(($this->purityTest1 + $this->purityTest2) / 2, 2)
            : 0;
    }

    public function getDeductedWeightProperty()
    {
        return round($this->netWeight * (1 - $this->presetDeductionPercent / 100), 3);
    }

    public function getSummaryTextProperty()
    {
        $customer = Customer::find($this->customerId);

        return implode("\n", [
            "Old Gold/Silver Exchange — Summary",
            "Customer: " . ($customer->name ?? '—') . " (" . ($customer->phone ?? '—') . ")",
            "Description: {$this->description}",
            "Gross weight (as received): {$this->grossWeight}g",
            "Net weight (after melting): {$this->netWeight}g",
            "Purity test 1: {$this->purityTest1}% · Purity test 2: {$this->purityTest2}%",
            "Average purity: {$this->averagePurity}%",
            "Preset deduction: {$this->presetDeductionPercent}%",
            "Net payable weight: {$this->deductedWeight}g",
        ]);
    }

    public function render()
    {
        return view('livewire.exchange.new-entry', [
            'customerResults' => $this->customerSearch
                ? Customer::where('name', 'like', "%{$this->customerSearch}%")
                    ->orWhere('phone', 'like', "%{$this->customerSearch}%")
                    ->limit(8)->get()
                : collect(),
        ])->layout('components.layouts.app', ['title' => 'Exchange — New Entry — Radharani Jewellery']);
    }
}
