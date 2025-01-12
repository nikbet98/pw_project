@extends('layout.master')
<link rel="stylesheet" href="{{ asset('css/cart.css') }}">
@section('active_products','home')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('messages.home_section')}}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('profile') }}">{{__('messages.profile_section')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('messages.wishlist_section')}}</li>
@endsection

@section('body')
<div class="container mt-5">
    <h1>La mia Lista dei Desideri</h1>

    @if ($wishlist->products->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>{{__('messages.image')}}</th>
                    <th>{{__('messages.product')}}</th>
                    <th>{{__('messages.price')}}</th>
                    <th>{{__('messages.remove')}}</th>
                    <th>{{__('messages.add_cart')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($wishlist->products as $product)
                    <tr data-product-id="{{ $product->id }}">
                        <td>
                            <a href="{{ route('product.show', $product->id) }}">
                                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="img-fluid" style="max-width: 50px;">
                            </a>
                        </td>
                        <td>
                            {{ $product->name }}
                        </td>
                        <td>
                            €{{ number_format($product->price, 2) }}
                        </td>
                        <td>
                            <button class="btn btn-sm btn-danger remove-from-wishlist">{{__('messages.remove')}}</button>
                        </td>
                        <td>
                            <button class="btn btn-primary add-to-cart">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>{{__('messages.wishlist_empty')}}.</p>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Remove item from wishlist
        document.querySelectorAll('.remove-from-wishlist').forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('tr');
                const productId = row.dataset.productId;

                fetch(`/profile/wishlist/remove/${productId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        row.remove();
                    }
                });
            });
        });

        // Add item to cart
        $('.add-to-cart').on('click', function() {
            var row = $(this).closest('tr');
            var productId = row.data('product-id');

            $.ajax({
                url: `/cart/add/${productId}`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    product_id: productId,
                    quantity: 1
                },
                success: function(response) {
                    if (response.success) {
                        row.remove();
                        updateCartCounter();
                    }
                }
            });
        });

        // Function to update cart counter
        function updateCartCounter() {
            fetch('{{ route('cart.total-count') }}')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('cart-counter').textContent = data.cart_count;
                });
        }
    });
</script>
@endsection