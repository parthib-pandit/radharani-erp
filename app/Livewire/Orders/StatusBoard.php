<?php
namespace App\Livewire\Orders;

use App\Models\Orders\Order;
use Livewire\Component;

class StatusBoard extends Component
{
    public function render()
    {
        $orders = Order::with('customer')->latest()->get()->groupBy('status');

        return view('livewire.orders.status-board', ['orders' => $orders])->layout('components.layouts.app', ['title' => 'Order Status Board — Radharani Jewellery']);
    }
}
