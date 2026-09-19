<?php
namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Model;

class Box extends Model
{
    protected $fillable = ['code', 'label'];

    public function packets()
    {
        return $this->hasMany(Packet::class);
    }

    public function movements()
    {
        return \App\Models\Movement\Movement::where('trackable_type', 'box')
            ->where('trackable_id', $this->id);
    }
}
