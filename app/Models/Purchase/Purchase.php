<?php
namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Purchase extends Model
{
    protected $fillable = [
        'vendor_id', 'invoice_number', 'total_weight', 'total_amount',
        'gst', 'payment_status', 'created_by',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function items()
    {
        return $this->belongsToMany(\App\Models\Stock\Item::class, 'purchase_items')
            ->withPivot('rate', 'weight');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
