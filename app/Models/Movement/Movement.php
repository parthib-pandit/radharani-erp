<?php
namespace App\Models\Movement;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Movement extends Model
{
    protected $fillable = [
        'trackable_type', 'trackable_id', 'movement_type', 'purpose_label',
        'user_id', 'counterparty', 'expected_return', 'actual_return',
        'weight_at_dispatch', 'photo_path', 'bill_path', 'note',
        'reverses_movement_id', 'approved_by',
    ];

    // Never update or delete a movement at the app layer.
    // Mistakes are corrected via a new 'correction' row referencing
    // reverses_movement_id, approved by an owner (approved_by).

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function reverses()
    {
        return $this->belongsTo(self::class, 'reverses_movement_id');
    }

    public function trackable()
    {
        return match ($this->trackable_type) {
            'item' => \App\Models\Stock\Item::find($this->trackable_id),
            'packet' => \App\Models\Stock\Packet::find($this->trackable_id),
            'box' => \App\Models\Stock\Box::find($this->trackable_id),
        };
    }
}
