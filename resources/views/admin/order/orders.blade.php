@extends('layout.adminMaster')
@section('active_orders','active')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{__('messages.home_section')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('messages.orders_section')}}</li>
@endsection

@section('body')
    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="h4 mb-0">{{__('messages.all_orders')}}</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>{{__('messages.order_id')}}</th>
                                        <th>{{__('messages.date')}}</th>
                                        <th>{{__('messages.user_id')}}</th>
                                        <th>{{__('messages.total')}}</th>
                                        <th>{{__('messages.order_state')}}</th>
                                        <th>{{__('messages.action')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>{{ $order->id }}</td>
                                            <td>{{ $order->date }}</td>
                                            <td>{{ $order->user_id }}</td>
                                            <td>€{{ number_format($order->total, 2) }}</td>
                                            <td>
                                                <span class="badge badge-{{$order->state === 'in attesa' ? 'warning' : ($order->state === 'spedito' ? 'success' : 'danger')}}">
                                                    {{ $order->state }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.order.show', $order->id) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

