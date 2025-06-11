<?php

namespace App\Jobs;

use App\Events\OrderPlaced;
use App\Models\Order as ModelsOrder;
use App\Models\product\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Broadcast;

class BroadcastOrderJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public $order;

    public function __construct(ModelsOrder $order)
    {
        // dump('Order Placed');

        $this->order = $order;
        OrderPlaced::dispatch(['order' => $this->order]);

    }

    /**
     * Execute the job.
     */
    public function handle()
    {

        // Broadcast the event using Reverb
        // Broadcast::channel('orders')->broadcast('.order.placed', [
        //     'id' => $this->order->id,
        //     'total_price' => $this->order->total_price,
        //     'items' => $this->order->items,
        // ]);
    }
}
