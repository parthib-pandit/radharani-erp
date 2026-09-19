<?php
namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'packet_id', 'huid_code', 'internal_code', 'category', 'purity',
        'weight', 'description', 'hsn_code', 'making_type', 'making_value',
        'pair_group_id', 'status',
    ];

    public function packet()
    {
        return $this->belongsTo(Packet::class);
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
