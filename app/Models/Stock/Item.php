<?php
namespace App\Models\Stock;

use App\Models\Movement\KarigarRawBatch;
use App\Models\Purchase\PurchaseItem;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    // Non-ambiguous charset for auto-generated codes: excludes 0/O, 1/I,
    // and L/S/5/8/B — visually close to each other on a small printed tag.
    // Confirmed requirement: 5 characters, alphanumeric, no ambiguous chars.
    public const CODE_ALPHABET = '234679ACDEFGHJKMNPQRTUVWXY';

    protected $fillable = [
        'packet_id', 'metal', 'huid_code', 'internal_code', 'category', 'purity',
        'weight', 'description', 'hsn_code', 'making_type', 'making_value',
        'pair_group_id', 'source_karigar_batch_id', 'source_purchase_item_id',
        'status',
    ];

    // Generates a unique 5-character internal code from a charset that
    // avoids visually ambiguous characters.
    public static function generateInternalCode(): string
    {
        do {
            $code = '';
            for ($i = 0; $i < 5; $i++) {
                $code .= self::CODE_ALPHABET[random_int(0, strlen(self::CODE_ALPHABET) - 1)];
            }
        } while (static::where('internal_code', $code)->exists());

        return $code;
    }

    public function packet()
    {
        return $this->belongsTo(Packet::class);
    }

    public function sourceKarigarBatch()
    {
        return $this->belongsTo(KarigarRawBatch::class, 'source_karigar_batch_id');
    }

    public function sourcePurchaseItem()
    {
        return $this->belongsTo(PurchaseItem::class, 'source_purchase_item_id');
    }

    public function movements()
    {
        return \App\Models\Movement\Movement::where('trackable_type', 'item')
            ->where('trackable_id', $this->id)
            ->orderByDesc('created_at');
    }

    // Latest movement = current location. Used by owner dashboard.
    public function currentMovement()
    {
        return $this->movements()->first();
    }

    public function pairedWith()
    {
        return static::where('pair_group_id', $this->pair_group_id)
            ->where('id', '!=', $this->id);
    }
}
