<?php
namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer\Customer;
use App\Models\User;

class Sale extends Model
{
    public $timestamps = false;
    protected $casts = ['additional_charges' => 'array', 'payment_modes' => 'array'];
    protected $fillable = [
        'customer_id', 'invoice_number', 'type', 'cgst', 'sgst', 'igst',
        'additional_charges', 'discount', 'payment_modes', 'accountant_note',
        'total', 'confirmed_by_accountant', 'created_by',
    ];

    protected static function booted()
    {
        static::creating(fn ($sale) => $sale->created_at ??= now());
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->belongsToMany(\App\Models\Stock\Item::class, 'sale_items')
            ->withPivot('price_at_sale');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
