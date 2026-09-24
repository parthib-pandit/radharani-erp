<?php
namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    public $timestamps = false;

    protected $fillable = ['target_type', 'target_id', 'code', 'file_path'];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function target()
    {
        return match ($this->target_type) {
            'item' => Item::find($this->target_id),
            'packet' => Packet::find($this->target_id),
            'box' => Box::find($this->target_id),
        };
    }
}
