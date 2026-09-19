<?php
namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name', 'phone', 'address', 'email', 'gstin', 'balance', 'status',
        'loyalty_points', 'referral_code', 'referred_by', 'imported_from_tally',
    ];

    public function sales()
    {
        return $this->hasMany(\App\Models\Sales\Sale::class);
    }

    public function loyaltyTransactions()
    {
        return $this->hasMany(LoyaltyTransaction::class);
    }

    public function installmentSchemes()
    {
        return $this->hasMany(InstallmentScheme::class);
    }

    public function referredBy()
    {
        return $this->belongsTo(self::class, 'referred_by');
    }
}
