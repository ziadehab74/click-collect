@push('styles')
    <style>
        .menulist-container {
            height: calc(100vh - 40px);
            /* full viewport height minus top margin */
            width: 1000px;
            /* fixed max width, adjust as you want */
            /* display: flex; */
            gap: 1rem;
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-top: 5rem;
        }

        .menulist-list {
            flex: 1 1 65%;
            overflow-y: auto;
            padding: 1rem;
        }

        .cart-container {
            flex: 0 0 35%;
            padding: 1rem;
            max-height: 100%;
            overflow-y: auto;
            border-left: 1px solid #eee;
            position: sticky;
            top: 0;
        }

        @media (max-width: 1200px) {
            .menulist-container {
                width: 90vw;
                flex-direction: column;
                height: auto;
            }

            .menulist-list,
            .cart-container {
                flex: 1 1 100%;
                max-height: none;
                border-left: none;
            }

            .cart-container {
                border-top: 1px solid #eee;
                position: static;
                margin-top: 1rem;
            }
        }

        nav.navbar {
            position: relative;
        }
    </style>
@endpush

<div class="container menulist-container">
    {{-- <h1 class="text-center mb-4">🍽️ Restaurant menulist</h1> --}}

    <div class="row">
        <div class="col-lg-8">
            @php
                $currentCategory = null;
            @endphp

            @foreach ($items as $item)
                @if ($currentCategory !== $item['category']['name'])
                    @php
                        $currentCategory = $item['category']['name'] ?? 'Uncategorized';
                    @endphp
                    <h3 class="mt-4 mb-3 border-bottom pb-2">{{ $currentCategory }}</h3>
                @endif

                <div class="card mb-3 shadow-sm">
                    <div class="row g-0 align-items-center">
                        <div class="col-md-3 text-center p-2">
                            <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['name'] }}"
                                class="img-fluid rounded" style="max-height: 100px; object-fit: cover;">
                        </div>
                        <div class="col-md-6">
                            <div class="card-body p-2">
                                <h5 class="card-title mb-1">{{ $item['name'] }}</h5>
                                <p class="card-text text-muted mb-0">${{ number_format($item['price'], 2) }}</p>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <button class="btn btn-success btn-sm" wire:click="addToCart({{ $item['id'] }})">
                                <i class="bi bi-cart-plus"></i> Add
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="col-lg-4">
            <div class="cart-container">
                <h4 class="text-center mb-3">🛒 Your Cart</h4>

                <ul class="list-group mb-3">
                    @forelse ($this->cart as $index => $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $item['name'] }}</strong>
                                <br>
                                <small>${{ number_format($item['price'], 2) }}</small>
                            </div>
                            <button class="btn btn-sm btn-danger" wire:click="removeFromCart({{ $index }})"
                                aria-label="Remove {{ $item['name'] }}">
                                &times;
                            </button>
                        </li>
                    @empty
                        <li class="list-group-item text-muted text-center">Your cart is empty.</li>
                    @endforelse
                </ul>

                <h5 class="text-center">Total: <span class="text-success">${{ number_format($totalPrice, 2) }}</span>
                </h5>

                <button class="btn btn-primary w-100 mt-3" wire:click="checkout"
                    {{ empty($this->cart) ? 'disabled' : '' }}>
                    Checkout
                </button>

                @if (session()->has('success'))
                    <div class="alert alert-success mt-3" role="alert">{{ session('success') }}</div>
                @endif

                @if (session()->has('error'))
                    <div class="alert alert-danger mt-3" role="alert">{{ session('error') }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
