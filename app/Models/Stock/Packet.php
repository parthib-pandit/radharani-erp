<?php
namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Model;

class Packet extends Model
{
    protected $fillable = ['box_id', 'code', 'label'];

    public function box()
    {
        return $this->belongsTo(Box::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function movements()
    {
        return \App\Models\Movement\Movement::where('trackable_type', 'packet')
            ->where('trackable_id', $this->id);
    }
}
