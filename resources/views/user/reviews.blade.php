@extends('layout.master')

@section('active_reviews','home')

<link rel="stylesheet" href="{{ asset('css/user_review_card.css') }}"> 

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('profile') }}">Profilo</a></li>
    <li class="breadcrumb-item active" aria-current="page">Le mie recensioni</li>
@endsection

@section('body')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4">Le mie recensioni</h2>

            @if ($userReviews->count() > 0)
                @foreach ($userReviews as $review)
                    <div class="card user-review-card mb-3">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-10">
                                    {{ $review->title }} - 
                                    @for ($i = 0; $i < $review->stars; $i++)
                                        <i class="fas fa-star text-warning"></i>
                                    @endfor
                                    @for ($i = $review->stars; $i < 5; $i++)
                                        <i class="far fa-star text-warning"></i>
                                    @endfor
                                </div>
                                <div class="col-md-2">
                                    <a href="{{route('product.show', $review->product->id)}}">
                                        <img src="{{ $review->product->image }}" alt="{{ $review->product->name }}" class="img-fluid">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <p>Prodotto: {{ $review->product->name }}</p> <p>{{ $review->text }}</p>
                            <p class="text-muted">Recensito il: {{ $review->created_at }}</p>
                        </div>
                    </div>
                @endforeach
            @else
                <p>Non hai ancora recensito nessun prodotto.</p>
            @endif
        </div>
    </div>
</div>

@endsection
