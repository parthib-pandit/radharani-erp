<?php
namespace App\Services;

use App\Models\Stock\Item;
use App\Models\Movement\RateLog;
use App\Models\Pricing\DiscountRule;

class PricingService
{
    // Price is never stored on the item — always computed live from the
    // latest rate log. This is what makes a rate update instantly
    // reprice every product with zero writes.
    public function priceFor(Item $item): float
    {
        $metal = str_contains(strtolower($item->purity), 'silver') ? 'silver' : 'gold';
        $rate = RateLog::latestFor($metal)?->rate ?? 0;

        $base = $item->weight * $rate;

        $making = $item->making_type === 'percentage'
            ? $base * ($item->making_value / 100)
            : $item->making_value;

        $huidCharge = $item->huid_code ? 45 : 0;

        $price = round($base + $making + $huidCharge, 2);

        // Automatic discount rules (item > packet > box > category > weight
        // tier precedence) are applied here. This is separate from any
        // manual discount an accountant enters at checkout (sales.discount) —
        // that one stacks on top of this, applied in the Sales module.
        $rule = DiscountRule::bestFor($item);
        if ($rule) {
            $price = $rule->apply($price);
        }

        return $price;
    }
}
