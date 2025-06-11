<?php

namespace App\Http\Controllers;

use App\Events\OrderPlaced;
use App\Jobs\BroadcastOrderJob;
use App\Models\Item;
use App\Models\OrderItem;
use App\Models\product\Order;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function view()
    {
        $items = Item::with('category')->with('category.parent')->get()->toArray(); // Fetch items with their category
        return view('menu', compact('items'));
    }
}
