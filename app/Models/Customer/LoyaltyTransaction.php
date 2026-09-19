<?php
namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Model;

class LoyaltyTransaction extends Model
{
    const UPDATED_AT = null;
    protected $fillable = ['customer_id', 'points', 'reason', 'related_sale_id'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
