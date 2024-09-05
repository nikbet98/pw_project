@foreach ($products as $product)
    <div class="col-md-4 mb-4">
        @component('components.product_card',[
            'product' => $product,
        ])  
        @endcomponent
    </div>
@endforeach