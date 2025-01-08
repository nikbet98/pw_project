@extends('layout.master')

<!-- additiona style for this page -->
<link rel="stylesheet" href="{{ asset('css/product.css') }}">
<link rel="stylesheet" href="{{ asset('css/scrollable_list.css') }}">
<link rel="stylesheet" href="{{ asset('css/product_card.css') }}">
{{-- script --}}
<script src="{{ asset('js/reviews_pagination.js') }}"></script>


@section('active_products','active')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('messages.home_section')}}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('product.index') }}">{{__('messages.products_section')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
@endsection

@section('body')
    <div class="row">
        <div class="col-md-5">
            <div class="card">
            <img src="{{ $product->image }}" class="card-img-top" alt="{{ $product->name }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $product->name }}</h5>
                <p class="card-text">
                    <span class="font-weight-bold">{{__('messages.price')}}:</span> {{ $product->price }}
                </p>
                <p class="card-text">
                    <span class="font-weight-bold">{{__('messages.brand')}}:</span> {{ $product->brand->name }}
                </p>
                <p class="card-text">
                    <span class="font-weight-bold">{{__('messages.category')}}:</span> {{ $product->subcategory->category->name }}
                </p>
                <p class="card-text">
                    <span class="font-weight-bold">{{__('messages.subcategory')}}:</span> {{ $product->subcategory->name }}
                </p>
                <p class="card-text">
                    <span class="font-weight-bold">{{__('messages.description')}}:</span>
                </p>
                <p class="card-text">{{ $product->description }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">{{__('messages.shipping_info')}}</h6> 
                <ul class="list-unstyled">
                    <li>
                        <small>
                            <i class="fas fa-truck"></i> 
                            {{__('messages.delivery_time')}}
                        </small>
                    </li>
                    <li>
                        <small>
                            <i class="fas fa-box"></i> 
                            {{__('messages.delivery_cost')}}
                        </small>
                    </li>
                </ul>
    
                <hr> 
    
                <form action="{{ route('cart.add', $product->id) }}" method="POST" id="addToTheCartBtn"> 
                    @csrf
                    <input type="text" id="product_id" value="{{ $product->id }}" style="display: none;">
                    <input type="text" id="quantity" value="1" style="display: none;">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-cart-plus"></i> {{__('messages.add_cart')}}
                    </button>
                </form>
            </div>
        </div>
    </div>
    
</div>

@component('components.scrollable_product_list', [
    'products' => $similarProducts,
    'title' => __('messages.similar_products')])
@endcomponent

<!-- product reviews -->
{{-- <div class="row">
    <div class="col-md-5 offset-md-3">
        <h2 class="text-center">{{__('messages.user_reviews')}}</h2>
        <div id="reviews-container">
            @foreach ($reviews as $review)
                @component('components.review_card', [
                    'review' => $review
                    ])
                @endcomponent
            @endforeach
        </div>
        <div class="text-center mt-3">  
            {{ $reviews->links() }}
        </div>
    </div>
</div> --}}

<div class="row">
    <div class="col-md-5 offset-md-3">
        <h2 class="text-center">{{__('messages.user_reviews')}}</h2>
        <div id="reviews-container">
            @foreach ($reviews as $review)
                @component('components.review_card', [
                    'review' => $review
                    ])
                @endcomponent
            @endforeach
        </div>
        <div class="text-center mt-3" id="pagination-container">  
            {{ $reviews->links() }}
        </div>
    </div>
</div>
@endsection