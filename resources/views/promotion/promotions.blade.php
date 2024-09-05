@extends('layout.master')

@section('active_promotions','active')
<link rel="stylesheet" href="{{ asset('css/promotion_card.css') }}">

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('messages.home_section')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('messages.promotions_section')}}</li>
@endsection

@section('body')
    <div class="container-fluid">
        <div class="row">
            <div class="col-11 mx-auto">
                <h2 class="mb-4 text-center">{{__('messages.promotions_title')}}</h2>
                <div class="row">
                    @foreach ($promotions as $promotion)
                        <div class="col-12">
                            <div class="card promotion-card">
                                <a class="promotion-card-link" href="{{ route('promotion.show', $promotion) }}">
                                    <img src="{{ $promotion->image }}" class="card-img-top" alt="{{ $promotion->title }}">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $promotion->name }}</h5>
                                        <p class="card-text">{{ $promotion->description }}</p>
                                        <p class="card-text"><strong>{{__('messages.start')}}:</strong> {{ $promotion->start }}</p>
                                        <p class="card-text"><strong>{{__('messages.end')}}:</strong> {{ $promotion->end }}</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

@endsection