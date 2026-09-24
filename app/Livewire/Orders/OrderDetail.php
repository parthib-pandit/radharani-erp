<?php
namespace App\Livewire\Orders;

use App\Models\Notification\PendingNotification;
use App\Models\Orders\Order;
use Livewire\Component;

class OrderDetail extends Component
{
    public Order $order;

    public function mount(Order $order)
    {
        $this->order = $order->load('customer', 'stockItem', 'convertedSale');
    }

    public function confirm()
    {
        $this->order->update(['status' => 'confirmed']);
        $this->order->refresh();
    }

    public function markReady()
    {
        $this->order->update(['status' => 'ready']);
        $this->order->refresh();

        PendingNotification::create([
            'customer_id' => $this->order->customer_id,
            'type' => 'order_ready',
            'recipient_name' => $this->order->customer?->name,
            'recipient_phone' => $this->order->customer?->phone,
            'message' => "Your {$this->order->product_description} order is ready for pickup.",
            'status' => 'pending',
            'related_type' => 'order',
            'related_id' => $this->order->id,
            'created_by' => auth()->id(),
        ]);
    }

    public function deliver()
    {
        $this->order->update(['status' => 'delivered']);
        $this->order->refresh();
    }

    public function cancel()
    {
        $this->order->update(['status' => 'cancelled']);
        $this->order->refresh();
    }

    public function getConfirmationMessageProperty(): string
    {
        $o = $this->order;

        return "Radharani Jewellery Works — Order Confirmation\n"
            . "Dear {$o->customer?->name}, your order for \"{$o->product_description}\" (₹" . number_format((float) $o->estimated_value) . ") is confirmed.\n"
            . ($o->rate_locked ? "Rate locked at order value.\n" : "Rate will apply at delivery.\n")
            . "Track status anytime on your portal.";
    }

    public function render()
    {
        return view('livewire.orders.order-detail')->layout('components.layouts.app', ['title' => 'Order Detail — Radharani Jewellery']);
    }
}
