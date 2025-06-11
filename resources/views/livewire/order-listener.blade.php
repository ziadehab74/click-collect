<div>
    <h2 class="text-xl font-bold mb-4">Kitchen Display System</h2>
    <div class="grid grid-cols-3 ">
        @foreach($orders as $order)
            <div class="p-4 border rounded-lg shadow-md bg-white">
                <h3 class="text-lg font-bold">Order #{{ $order['id'] }}</h3>
                @foreach(array_slice($order['order_items'], 0, 5) as $item)
                <p >{{ $item['quantity'] }} : {{ $item['item']['name']}}   </p>
                @endforeach
                 @if(count($order['order_items']) > 5)
                        <span class="text-blue-500 cursor-pointer" >See more ...</span>
                    @endif
                <p class="text-gray-700 font-bold">Status: {{ $order['status'] }}</p>
                <div class="mt-2 space-x-2">
                    <button wire:click="updateStatus({{ $order['id'] }}, 'Preparing')" class="bg-yellow-500 text-white px-3 py-1 rounded">Preparing</button>
                    <button wire:click="updateStatus({{ $order['id']}}, 'Ready')" class="bg-green-500 text-white px-3 py-1 rounded">Ready</button>
                    {{-- <button wire:click="updateStatus({{ $order['id'] }}, 'Served')" class="bg-blue-500 text-white px-3 py-1 rounded">Served</button> --}}
                </div>
            </div>
        @endforeach
    </div>
</div>