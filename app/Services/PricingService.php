<?php
namespace App\Services;

use App\Models\Stock\Item;
use App\Models\Movement\RateLog;

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

        return round($base + $making + $huidCharge, 2);
    }
}
