<?php

namespace App\Livewire;

use App\Events\OrderPlaced;
use App\Jobs\BroadcastOrderJob;
use App\Models\Item;
use App\Models\OrderItem;
use App\Models\Order;
// use App\Models\product\Order as ProductOrder;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;


class MenuCheckout extends Component
{
    public $items;
    public $cart = [];
    public $totalPrice = 0;

    public function mount()
    {
        $this->items = Item::with('category')->get();
    }

    public function addToCart($itemId)
    {
        $item = Item::find($itemId);

        if ($item) {
            $this->cart[] = [
                'id' => $item->id,
                'name' => $item->name,
                'price' => $item->price,
            ];
            $this->totalPrice += $item->price;
        }
    }


    public function checkout()
    {
        if (empty($this->cart)) {
            session()->flash('error', 'Your cart is empty!');
            return;
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'total_amount' => $this->totalPrice,
            'status' => 'pending', // Fixed typo
            'order_date' => now(),
        ]);

        foreach ($this->cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'item_id' => $item['id'],
                'subtotal' => $item['price'],
                'quantity' => 1,
            ]);
        }

        // Load orderItems relationship
        $order->load('orderItems');
        $order->load('orderItems.item');

        // Dispatch event with order and its items
        event(new OrderPlaced($order));

        // Reset cart
        $this->cart = [];
        $this->totalPrice = 0;

        session()->flash('success', 'Order placed successfully!');
    }

    public function removeFromCart($index)
    {
        if (isset($this->cart[$index])) {
            $this->totalPrice -= $this->cart[$index]['price'];
            array_splice($this->cart, $index, 1);
        }
    }

    public function render()
    {
        return view('livewire.menu-checkout');
    }
}
