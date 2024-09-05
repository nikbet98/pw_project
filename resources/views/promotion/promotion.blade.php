@extends('layout.master')
<link rel="stylesheet" href="{{ asset('css/product_card.css') }}">
@section('active_promotions','active')

@section('breadcrumb')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('product.index') }}">Promozioni</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $promotion->name }}</li>
@endsection

@section('body')
<div class="col-md-9 mx-auto">
    <div class="row", id="productGrid">
        <!-- Griglia dei prodotti -->
        @foreach ($products as $product)
            <div class="col-md-4 mb-4">
                @component('components.product_card',[
                        'product'=>$product,
                    ])  
                @endcomponent
            </div>
        @endforeach
    </div>
</div>
@endsection