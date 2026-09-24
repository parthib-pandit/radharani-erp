<?php
namespace App\Models\Movement;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class Movement extends Model
{
    use LogsActivity;

    protected $fillable = [
        'trackable_type', 'trackable_id', 'movement_type', 'purpose_label',
        'user_id', 'counterparty', 'expected_return', 'actual_return',
        'weight_at_dispatch', 'weight_at_return', 'weight_loss', 'tagged_by',
        'photo_path', 'bill_path', 'note',
        'reverses_movement_id', 'approved_by',
    ];

    // Feeds the Audit Log Viewer (Section 17). Movements are insert-only
    // (rule 1), so only "created" events will ever fire here — which is
    // exactly the tamper-evident trail that rule exists for.
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['trackable_type', 'trackable_id', 'movement_type', 'user_id', 'counterparty', 'reverses_movement_id'])
            ->useLogName('movement');
    }

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
