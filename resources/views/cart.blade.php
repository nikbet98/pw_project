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
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>
                            <a href="{{ route('product.show', $product->id)}}">
                                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="img-fluid" style="max-width: 50px;"> 
                            </a>
                        </td>
                        <td>
                            {{ $product->name }} 
                        </td>
                        <td>
                            <form action="{{ route('cart.update', $product->id) }}" method="POST">
                                @csrf
                                <input type="number" name="quantity" value="{{ $cartItems[$product->id]['quantity'] }}" min="1">
                                <button type="submit" class="btn btn-sm btn-primary">{{__('messages.update')}}</button>
                            </form>
                        </td>
                        <td>
                            @if(isset($cartItems[$product->id]['discountedPrice'])) 
                                {{-- Display discounted price if available --}}
                                {{ $cartItems[$product->id]['discountedPrice'] }} 
                            @else
                                {{ $product->price }} 
                            @endif
                        </td>
                        <td>
                            @if(isset($cartItems[$product->id]['discountedPrice']))
                                {{-- Calculate total using discounted price --}}
                                {{ $cartItems[$product->id]['discountedPrice'] * $cartItems[$product->id]['quantity'] }}
                            @else
                                {{ $product->price * $cartItems[$product->id]['quantity'] }}
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('cart.remove', $product->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger">{{__('messages.remove')}}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{-- Total Cost --}}
        <div class="d-flex justify-content-end mt-3"> 
            <strong>Totale Carrello: {{ $totalCartCost }}</strong> 
        </div>
        
        <div class="d-flex justify-content-end mt-3">
            <form action="{{ route('cart.clear') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger mr-2">{{__('messages.clear_cart')}}</button>
            </form>

            {{-- Proceed to Checkout Button --}}
            <a href="{{ route('checkout.index')}}" class="btn btn-success">{{__('messages.checkout')}}</a> 
        </div>

    @else
        <p>{{__('messages.cart_empty')}}.</p>
    @endif
</div>
@endsection
