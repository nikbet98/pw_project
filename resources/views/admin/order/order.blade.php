@extends('layout.adminMaster')
@section('active_orders','active')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{__('messages.home_section')}}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">{{__('messages.orders_section')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{$order->id}}</li>
@endsection

@section('body')
    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="h4 mb-0">{{__('messages.order_detail_title')}} #{{ $order->id }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>{{__('messages.order_info')}}</h4>
                                    <p><strong>{{__('messages.order_id')}}:</strong> {{ $order->id }}</p>
                                    <p><strong>{{__('messages.date')}}:</strong> {{ $order->date }}</p>
                                    <p><strong>{{__('messages.user_id')}}:</strong> {{ $order->user_id }}</p>
                                    <p><strong>{{__('messages.total')}}:</strong> €{{ number_format($order->total, 2) }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h4>{{__('messages.order_state')}}</h4>
                                    <form action="{{ route('admin.order.update', $order->id) }}" method="POST">
                                        @csrf
                                        @method('PUT') 
                                        <div class="form-group">
                                            <select name="state" class="form-control">
                                                <option value="pending" {{ $order->state === 'pending' ? 'selected' : '' }}>In attesa</option>
                                                <option value="dispatched" {{ $order->state === 'dispatched' ? 'selected' : '' }}>Spedito</option>
                                                <option value="delivered" {{ $order->state === 'delivered' ? 'selected' : '' }}>Consegnato</option>
                                                <option value="eliminated" {{ $order->state === 'eliminated' ? 'selected' : '' }}>Cancellato</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">{{__('messages.update_state')}}</button>
                                    </form>
                                </div>
                            </div>

                            <hr>

                            <h4>{{__('messages.order_products')}}</h4>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{__('messages.name')}}</th>
                                        <th>{{__('messages.price')}}</th>
                                        <th>{{__('messages.discount')}}</th>
                                        <th>{{__('messages.quantity')}}</th>
                                        <th>{{__('messages.subtotal')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($order->products as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>€{{ number_format($product->pivot->price, 2) }}</td>
                                        <td>{{ $product->pivot->discount * 100 }}%</td>
                                        <td>{{ $product->pivot->quantity }}</td>
                                        <td>€{{ number_format(($product->pivot->price - ($product->pivot->price * $product->pivot->discount)) * $product->pivot->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            <hr>

                            <div class="d-flex justify-content-end">
                                <form action="{{ route('admin.order.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Sei sicuro di voler eliminare questo ordine?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">{{__('messages.delete_order')}}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
