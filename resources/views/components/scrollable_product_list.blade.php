
<div class="row">
    <div class="col-12 text-center mb-3">
        <h2 class="mb-0">{{ $title }}</h2>
    </div>
    <div class="col-12">
        <div class="card-group-scroll">
            @foreach ($products as $product)
                @component('components.product_card',[
                    'product'=>$product
                ])
                @endcomponent
            @endforeach
        </div>
    </div>
</div>