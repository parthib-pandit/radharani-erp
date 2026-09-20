<?php
namespace App\Models\Pricing;

use Illuminate\Database\Eloquent\Model;
use App\Models\Stock\Item;

class DiscountRule extends Model
{
    protected $fillable = [
        'scope', 'scope_ref_id', 'category', 'min_weight', 'max_weight',
        'discount_type', 'value', 'active', 'valid_from', 'valid_to', 'created_by',
    ];

    protected $casts = ['active' => 'boolean', 'valid_from' => 'date', 'valid_to' => 'date'];

    public function isCurrentlyValid(): bool
    {
        if (! $this->active) return false;
        $today = now()->toDateString();
        if ($this->valid_from && $today < $this->valid_from->toDateString()) return false;
        if ($this->valid_to && $today > $this->valid_to->toDateString()) return false;
        return true;
    }

    // Precedence: item-level rule wins, then packet, then box, then category,
    // then weight_tier. Only one rule applies — first match found, in that order.
    public static function bestFor(Item $item): ?self
    {
        $candidates = static::where('active', true)->get()->filter->isCurrentlyValid();

        $item_rule = $candidates->firstWhere(fn ($r) => $r->scope === 'item' && $r->scope_ref_id === $item->id);
        if ($item_rule) return $item_rule;

        if ($item->packet_id) {
            $packet_rule = $candidates->firstWhere(fn ($r) => $r->scope === 'packet' && $r->scope_ref_id === $item->packet_id);
            if ($packet_rule) return $packet_rule;

            $box_id = $item->packet?->box_id;
            if ($box_id) {
                $box_rule = $candidates->firstWhere(fn ($r) => $r->scope === 'box' && $r->scope_ref_id === $box_id);
                if ($box_rule) return $box_rule;
            }
        }

        $category_rule = $candidates->firstWhere(fn ($r) => $r->scope === 'category' && $r->category === $item->category);
        if ($category_rule) return $category_rule;

        $weight_rule = $candidates->first(fn ($r) => $r->scope === 'weight_tier'
            && (is_null($r->min_weight) || $item->weight >= $r->min_weight)
            && (is_null($r->max_weight) || $item->weight <= $r->max_weight));

        return $weight_rule;
    }

    public function apply(float $price): float
    {
        return $this->discount_type === 'percentage'
            ? round($price - ($price * $this->value / 100), 2)
            : round(max(0, $price - $this->value), 2);
    }
}
