@extends('layout.master')

@section('active_products','home')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('messages.home_section')}}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('profile') }}">{{__('messages.profile_section')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('messages.orders_section')}}</li>
@endsection

@section('body')
<div class="container mt-5">
    <h2>{{__('messages.orders_title')}}</h2>

    @if ($orders->count() > 0)
        @foreach ($orders as $order)
            <table class="table">
                <thead>
                    <tr>
                        <th>{{__('messages.order_id')}}</th>
                        <th>{{__('messages.date')}}</th>
                        <th>{{__('messages.total')}}</th>
                        <th>{{__('messages.order_state')}}</th>
                    </tr>
                </thead>
                <tbody>
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->date}}</td>
                            <td>€{{ number_format($order->total, 2) }}</td>
                            <td>{{ $order->state}}</td>
                        </tr>
                </tbody>
            </table>
            <div class="col-md-10 mx-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{__('messages.product')}}</th>
                            <th>{{__('messages.price')}}</th>
                            <th>{{__('messages.discount')}}</th>
                            <th>{{__('messages.quantity')}}</th>
                            <th>{{__('messages.subtotal')}}</th>
                            <th>{{__('messages.make_review')}}</th>
                        </tr>
                    </thead>
                    <tbody> 
                        @foreach ($order->products as $product)
                            <tr> 
                                @php 
                                    $quantity = $product->pivot->quantity;
                                    $discount = $product->promotion()->first() ? $product->promotion()->first()->pivot->discount : 0;
                                    $subtotal = ($product->price - ($product->price * $discount)) * $quantity;
                                @endphp
                                <td>{{$product->name}}</td> 
                                <td>{{$product->price}}</td> 
                                <td>{{($discount)*100}}%</td> 
                                <td>{{$quantity}}</td> 
                                <td>{{number_format($subtotal,2)}}</td> 
                                <td>
                                    <a href="{{ route('product.review.create', $product->id) }}" class="btn btn-primary btn-sm">{{__('messages.make_review')}}</a>
                                </td>
                            </tr>
                            
                        @endforeach
                    </tbody> 
                </table>
            </div>
        @endforeach
    @else
        <p>{{__('messages.order_empty')}}.</p>
    @endif
</div>
@endsection
