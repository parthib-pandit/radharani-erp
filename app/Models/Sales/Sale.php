<?php
namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer\Customer;
use App\Models\User;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class Sale extends Model
{
    use LogsActivity;

    public $timestamps = false;
    // created_at needs an explicit cast because $timestamps=false stops
    // Eloquent's default date-casting too — without this, ->created_at is
    // a raw string and every ->format() call on it (SalesHistory,
    // InvoiceView, the portal dashboard) fatal-errors.
    protected $casts = ['additional_charges' => 'array', 'payment_modes' => 'array', 'created_at' => 'datetime'];
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

    // Feeds the Audit Log Viewer (Section 17). Sales rows are insert-only
    // (rule 1), so only "created" fires — a clean audit trail per sale.
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['customer_id', 'invoice_number', 'type', 'total', 'confirmed_by_accountant', 'created_by'])
            ->useLogName('sale');
    }
}
