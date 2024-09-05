<div class="card card-product">
    <img src="{{$product->image}}" class="card-img-top" alt="{{ $product->name }}">
    <div class="card-body">
        <h5 class="card-title">{{ $product->name }}</h5>
        <p class="card-text">{{ $product->description }}</p>
        @if ($product->promotion->isNotEmpty())
            @php
                $discountedPriceData = \App\Helpers\Helpers::getDiscountedPrice($product, $product->promotion->first());
                $discountedPrice = $discountedPriceData['discountedPrice'];
                $discount = $discountedPriceData['discount'];
            @endphp
            <p class="card-text"> {{__('messages.price')}}:
                <s class="text-decoration-line-through">{{ $product->price }}</s>
                <span class="text-success"> {{ $discountedPrice }}  -{{ $discount }}%</span>
            </p>
        @else
            <p class="card-text">{{__('messages.price')}}: {{ $product->price }}</p>
        @endif
    </div>
    <p class="card-text"> 
        @if ($product->reviews->count() > 0)
        <span class="text-warning card-stars">
            @php $avgRating =  round($product->reviews->avg('stars')); @endphp 
            @for ($i = 1; $i <= 5; $i++)
                @if ($i <= $avgRating)
                    <i class="fas fa-star"></i> 
                @else
                    <i class="far fa-star"></i> 
                @endif
            @endfor
        </span>
        {{ number_format($product->reviews->avg('stars'), 1) }}
        @else
            {{__('messages.no_review')}}
        @endif
    </p>    
    <div class="card-footer d-flex justify-content-center align-items-center">
        <form action="{{ route('product.show', $product->id) }}">
            <button type="submit" class="btn btn-primary mx-3">
                <i class="fas fa-eye"></i>
            </button>
        </form>
        <form action="{{ route('cart.add', $product->id) }}" method="POST" id="addToTheCartBtn">
            @csrf
            <input type="text" id="product_id" value="{{ $product->id }}" style="display: none;">
            <input type="text" id="quantity" value="1" style="display: none;">
            <button type="submit" class="btn btn-primary mx-3">
                <i class="fas fa-cart-plus"></i>
            </button>
        </form> 
        <form action="">
            <button type="button" class="btn btn-danger mx-3" id="addToWishlistBtn" data-product-id="{{ $product->id }}">
                <i class="fas fa-heart"></i> 
            </button>
        </form>          
    </div>
</div>