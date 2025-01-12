@extends('layout.master')
<link rel="stylesheet" href="{{ asset('css/cart.css') }}">
@section('active_products','home')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('messages.home_section')}}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('profile') }}">{{__('messages.profile_section')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('messages.cart_section')}}</li>
@endsection
@section('body')
<div class="container">
    <h1>{{__('messages.cart_title')}}</h1>

    @if(count($products) > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>{{__('messages.product')}}</th>
                    <th>{{__('messages.name')}}</th>
                    <th>{{__('messages.quantity')}}</th>
                    <th>{{__('messages.price')}}</th>
                    <th>{{__('messages.total')}}</th>
                    <th>{{__('messages.action')}}</th>
                </tr>
            </thead>
            <tbody id="cart-items">
                @foreach($products as $product)
                    <tr data-product-id="{{ $product->id }}">
                        <td>
                            <a href="{{ route('product.show', $product->id)}}">
                                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="img-fluid" style="max-width: 50px;"> 
                            </a>
                        </td>
                        <td>{{ $product->name }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <input type="number" name="quantity" value="{{ $cartItems[$product->id]['quantity'] }}" min="1" class="form-control quantity-input mr-2">
                                <button class="btn btn-sm btn-primary update-quantity">{{__('messages.update')}}</button>
                            </div>
                        </td>
                        <td>
                            @if(isset($cartItems[$product->id]['discountedPrice'])) 
                                {{ $cartItems[$product->id]['discountedPrice'] }} 
                            @else
                                {{ $product->price }} 
                            @endif
                        </td>
                        <td class="item-total">
                            @if(isset($cartItems[$product->id]['discountedPrice']))
                                {{ $cartItems[$product->id]['discountedPrice'] * $cartItems[$product->id]['quantity'] }}
                            @else
                                {{ $product->price * $cartItems[$product->id]['quantity'] }}
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-sm btn-danger remove-item">{{__('messages.remove')}}</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{-- Total Cost --}}
        <div class="d-flex justify-content-end mt-3"> 
            <strong id="total-cart-cost">Totale Carrello: {{ $totalCartCost }}</strong> 
        </div>
        
        <div class="d-flex justify-content-end mt-3">
            <button class="btn btn-danger mr-2 clear-cart">{{__('messages.clear_cart')}}</button>
            {{-- Proceed to Checkout Button --}}
            <a href="{{ route('checkout.index')}}" class="btn btn-success">{{__('messages.checkout')}}</a> 
        </div>

    @else
        <p>{{__('messages.cart_empty')}}.</p>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Function to update cart counter
        function updateCartCounter() {
            fetch('{{ route('cart.total-count') }}')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('cart-counter').textContent = data.cart_count;
                });
        }

        // Update quantity
        document.querySelectorAll('.update-quantity').forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('tr');
                const productId = row.dataset.productId;
                const quantity = row.querySelector('.quantity-input').value;

                fetch(`/cart/update/${productId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ quantity: quantity })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        row.querySelector('.item-total').textContent = data.itemTotal;
                        document.getElementById('total-cart-cost').textContent = `Totale Carrello: ${data.totalCartCost}`;
                        updateCartCounter();
                    }
                });
            });
        });

        // Remove item
        document.querySelectorAll('.remove-item').forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('tr');
                const productId = row.dataset.productId;

                fetch(`{{ url('cart/remove') }}/${productId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        row.remove();
                        document.getElementById('total-cart-cost').textContent = `Totale Carrello: ${data.totalCartCost}`;
                        updateCartCounter();
                    }
                });
            });
        });

        // Clear cart
        document.querySelector('.clear-cart').addEventListener('click', function() {
            fetch(`{{ url('cart/clear') }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('cart-items').innerHTML = '';
                    document.getElementById('total-cart-cost').textContent = `Totale Carrello: 0`;
                    updateCartCounter();
                }
            });
        });
    });
</script>
@endsection