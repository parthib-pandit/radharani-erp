<?php
namespace App\Models\Exchange;

use App\Models\Customer\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ExchangeTransaction extends Model
{
    protected $fillable = [
        'customer_id', 'gross_weight', 'description', 'net_weight',
        'purity_test_1', 'purity_test_2', 'purity_averaged',
        'preset_deduction_percent', 'deductable_weight', 'stage',
        'final_value', 'settled_by', 'settled_at', 'created_by',
    ];

    protected $casts = ['settled_at' => 'datetime'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function settler()
    {
        return $this->belongsTo(User::class, 'settled_by');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
