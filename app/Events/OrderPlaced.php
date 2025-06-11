<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\ShouldBroadcastNow;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow as BroadcastingShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderPlaced implements BroadcastingShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function broadcastOn()
    {
        return new Channel('orders'); // Public channel
    }

    public function broadcastWith()
    {
        return [
            'order' => $this->order,
        ];
    }
}
