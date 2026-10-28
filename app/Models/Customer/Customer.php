<?php
namespace App\Models\Customer;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// Authenticatable — customers log in through a separate 'customer' guard,
// completely independent of the staff `users` table/guard. Never merge
// these two — a customer must never be assignable a staff role.
class Customer extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'phone', 'address', 'email', 'password', 'gstin', 'balance', 'status',
        'referral_code', 'referral_opted_at', 'referred_by', 'imported_from_tally', 'is_walk_in',
    ];

    protected $casts = ['is_walk_in' => 'boolean'];

    protected $hidden = ['password', 'remember_token'];

    // GSTIN is kept upper case with no spaces, or null, wherever it is entered.
    public function setGstinAttribute($value): void
    {
        $this->attributes['gstin'] = \App\Rules\Gstin::normalize($value);
    }

    /** The shared default customer for a buyer who gives no details. Made if it is missing. */
    public static function walkIn(): self
    {
        return static::where('is_walk_in', true)->first()
            ?? static::create(['name' => 'Walk-in customer', 'phone' => 'walk-in', 'status' => 'past_customer', 'is_walk_in' => true]);
    }

    /** Real customers: everyone but the walk-in record (orders, exchanges, schemes and the like need someone to contact). */
    public function scopeReal($query)
    {
        return $query->where('is_walk_in', false);
    }

    public function sales()
    {
        return $this->hasMany(\App\Models\Sales\Sale::class);
    }

    public function installmentSchemes()
    {
        return $this->hasMany(InstallmentScheme::class);
    }

    public function referredBy()
    {
        return $this->belongsTo(self::class, 'referred_by');
    }

    // Customers this one referred (inverse of referredBy) — used by the
    // owner-facing Referral Overview report.
    public function referrals()
    {
        return $this->hasMany(self::class, 'referred_by');
    }
}
