<?php

namespace App\Livewire;

use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\On;

class OrderListener extends Component
{
    public $orders = [];

    public function getListeners()
    {
        return [
            "echo:orders,OrderPlaced" => 'loadOrders',

        ];
    }

    public function mount()
    {
        $this->refreshOrders();
    }

    public function refreshOrders()
    {
        $this->orders = Order::where('status', '!=', 'Served')
            ->latest()
            ->take(20)
            ->with(['orderItems', 'orderItems.item'])
            ->get()->toArray();
        // dd($this->orders);
    }
    // public function updateStatus($orderId, $status)
    // {
    //     $order = Order::find($orderId);
    //     if ($order) {
    //         $order->update(['status' => $status]);
    //         Broadcast::event(new \App\Events\OrderUpdated($order));
    //         $this->refreshOrders();
    //     }
    // }
    public function loadOrders($eventData)
    {
        Log::info('Received OrderPlaced event:', ['eventData' => $eventData]);
        if (!isset($eventData['order'])) {
            Log::error('Invalid event data received:', ['eventData' => $eventData]);
            return;
        }

        // Prepend new order to the list
        $this->orders = array_merge([$eventData['order']], $this->orders);

        // dump($this->orders);
    }

    public function render()
    {
        return view('livewire.order-listener');
    }
}
