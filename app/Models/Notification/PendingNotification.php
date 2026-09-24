<?php
namespace App\Models\Notification;

use App\Models\Customer\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class PendingNotification extends Model
{
    protected $fillable = [
        'customer_id', 'type', 'recipient_name', 'recipient_phone', 'message',
        'status', 'sent_by', 'sent_at', 'related_type', 'related_id', 'created_by',
    ];

    protected $casts = ['sent_at' => 'datetime'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    public function markSent(User $by): void
    {
        $this->update(['status' => 'sent', 'sent_by' => $by->id, 'sent_at' => now()]);
    }
}
