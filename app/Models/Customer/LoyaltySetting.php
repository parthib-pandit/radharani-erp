<?php
namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Model;

// Single-row settings table. Always use current() — never query this
// table directly elsewhere, so the "one row" rule stays enforced in
// exactly one place.
class LoyaltySetting extends Model
{
    protected $fillable = [
        'points_per_rupee', 'referral_bonus_points',
        'min_redeemable_points', 'point_value_in_rupees', 'updated_by',
    ];

    public static function current(): self
    {
        return static::first() ?? static::create([]);
    }
}
