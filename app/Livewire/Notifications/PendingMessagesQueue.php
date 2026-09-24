<?php
namespace App\Livewire\Notifications;

use App\Models\Notification\PendingNotification;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Pending Messages Queue — real data.
 *
 * WhatsApp auto-send is not being integrated this phase: every
 * customer-facing notification (sale confirmation, order ready, loyalty
 * award, instalment reminder, exchange valuation ready) is generated here
 * as a copyable message. Staff copies it and sends it manually (WhatsApp,
 * SMS, whatever), then marks it "sent" in this log.
 */
class PendingMessagesQueue extends Component
{
    use WithPagination;

    public string $statusFilter = 'pending';

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function markSent(int $id)
    {
        PendingNotification::findOrFail($id)->markSent(auth()->user());
    }

    public function render()
    {
        return view('livewire.notifications.pending-messages-queue', [
            'messages' => PendingNotification::with('customer')
                ->when($this->statusFilter !== 'all', fn ($q) => $q->where('status', $this->statusFilter))
                ->latest()
                ->paginate(25),
        ])->layout('components.layouts.app', ['title' => 'Pending Messages Queue — Radharani Jewellery ERP']);
    }
}
