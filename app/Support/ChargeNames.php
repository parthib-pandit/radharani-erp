<?php
namespace App\Support;

use App\Models\Pricing\PricingRule;
use App\Models\Stock\Item;

/**
 * Names offered when someone types an additional charge, so the staff pick "Sakha" instead of typing it. Only
 * suggestions: any name can still be typed. They come from what the owner has already set up (additional charge
 * pricing rules), what pieces already carry, and a few the shop uses.
 */
class ChargeNames
{
    public const STARTERS = ['Sakha', 'Pola', 'Tarsel', 'Polish', 'Packing'];

    private static ?array $cache = null;

    /** @return list<string> */
    public static function list(): array
    {
        return self::$cache ??= collect(self::STARTERS)
            ->merge(PricingRule::where('kind', 'additional')->whereNotNull('name')->pluck('name'))
            ->merge(Item::whereNotNull('default_additional_charges')->limit(2000)->pluck('default_additional_charges')
                ->flatMap(fn ($v) => collect(is_string($v) ? json_decode($v, true) : $v)->pluck('name')))
            ->map(fn ($n) => trim((string) $n))->filter()
            ->unique(fn ($n) => mb_strtolower($n))->sort(SORT_NATURAL | SORT_FLAG_CASE)->values()->all();
    }

    public static function forget(): void
    {
        self::$cache = null;
    }
}
