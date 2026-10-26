<?php
namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Box extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = ['code', 'label', 'home_vault_id'];

    // Renames and creation feed the Box Detail history timeline.
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['code', 'label', 'home_vault_id'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges()
            ->useLogName('stock');
    }

    public function audits()
    {
        return $this->hasMany(StockAudit::class);
    }

    public function packets()
    {
        return $this->hasMany(Packet::class);
    }

    /** The vault this box is kept in. Empty means the first vault. */
    public function homeVault()
    {
        return $this->belongsTo(\App\Models\Location::class, 'home_vault_id');
    }

    public function items()
    {
        return $this->hasManyThrough(Item::class, Packet::class);
    }

    /** The pieces still in the shop's custody: everything but the sold ones (those stay linked, for the history). */
    public function custodyItems()
    {
        return $this->hasManyThrough(Item::class, Packet::class)->where('items.status', '!=', 'sold');
    }

    public function qrCodes()
    {
        return $this->hasMany(QrCode::class, 'target_id')->where('target_type', 'box');
    }

    public function movements()
    {
        return \App\Models\Movement\Movement::where('trackable_type', 'box')
            ->where('trackable_id', $this->id);
    }
}
