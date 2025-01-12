@extends('layout.master')

<!-- additiona style for this page -->
<link rel="stylesheet" href="{{ asset('css/product_card.css') }}">
<link rel="stylesheet" href="{{ asset('css/scrollable_list.css') }}">
<link rel="stylesheet" href="{{ asset('css/gallery.css') }}">


@section('active_home','active')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ __('messages.home_section')}}</li>
@endsection

@section('body')

<!-- Scrolling Gallery for Promotions -->
<h2 class="text-center mx-auto">{{ __('messages.active_promotion')}}</h2>
<div id="promotionCarousel" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
      @foreach($promotions as $promotion)
        <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
            <a href="{{ route('promotion.show', $promotion->id)}}">
                <img src="{{ $promotion->image }}" class="d-block w-100" alt="{{ $promotion->title }}">
            </a>
        </div>
      @endforeach
    </div>
  
    <a class="carousel-control-prev" href="#promotionCarousel" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#promotionCarousel" role="button" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
</div>

<!-- best product -->
@component('components.scrollable_product_list',[
    'products' => $bestProducts,
    'title' =>  __('messages.best_product'),
])  
@endcomponent

<!-- latest product -->
@component('components.scrollable_product_list',[
    'products' => $latestProducts,
    'title' =>  __('messages.latest_product')
])
@endcomponent

<div class="col-md-9 mx-auto">
    <h2 class="text-center mx-auto">{{ __('messages.partner')}}</h2>
    <div class="row justify-content-center align-items-center">
        <!-- Griglia dei loghi dei brand -->
        @foreach ($brands as $brand)
            <div class="col-6 col-md-4 col-lg-2 mb-4 d-flex justify-content-center align-items-center">
                <a href="#">
                    <img src="{{ $brand->image }}" alt="{{ $brand->name }}" class="img-fluid brand-logo">
                </a>
            </div>
        @endforeach
    </div>
</div>

@endsection
