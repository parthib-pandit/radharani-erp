<?php
namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class Purchase extends Model
{
    use LogsActivity;

    protected $fillable = [
        'vendor_id', 'type', 'invoice_number', 'total_weight', 'total_amount',
        'gst', 'payment_status', 'created_by',
    ];

    // Feeds the Audit Log Viewer (Section 17). Purchases rows are
    // insert-only (rule 1), so only "created" fires here.
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['vendor_id', 'type', 'invoice_number', 'total_amount', 'payment_status', 'created_by'])
            ->useLogName('purchase');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    // All purchase lines (finished-product with an item, or raw-material
    // lines with tag_pending=true and no item yet).
    public function lines()
    {
        return $this->hasMany(PurchaseItem::class);
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
