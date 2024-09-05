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
                    <tr>
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
                            <form action="{{ route('profile.wishlist.remove', $product->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">{{__('messages.remove')}}</button>
                            </form>
                        </td>
                        <td>
                            <form action="{{ route('cart.add', $product->id) }}" method="POST" id="addToTheCartBtn">
                                @csrf
                                <input type="text" id="product_id" value="{{ $product->id }}" style="display: none;">
                                <input type="text" id="quantity" value="1" style="display: none;">
                                <button type="submit" class="btn btn-primary mx-4">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>{{__('messages.wishlist_empty')}}.</p>
    @endif
</div>
@endsection
