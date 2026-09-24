<?php
namespace App\Models\Purchase;

use App\Models\Stock\Item;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'purchase_id', 'item_id', 'description', 'category', 'metal',
        'purity', 'tag_pending', 'rate', 'weight',
    ];

    protected $casts = ['tag_pending' => 'boolean'];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // The item created once this raw-material line gets tagged in Stock.
    public function taggedItem()
    {
        return $this->hasOne(Item::class, 'source_purchase_item_id');
    }
}
