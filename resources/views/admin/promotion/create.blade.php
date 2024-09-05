@extends('layout.adminMaster')

@section('active_promotions', 'active')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{__('messages.home_section')}}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.promotions.index') }}">{{__('messages.promotions_section')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('messages.create_promotion_section')}}</li>
@endsection

@section('body')
    <form action="{{ route('admin.promotion.store') }}" method="POST"> 
        @csrf 

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="image" class="font-weight-bold">{{__('messages.url_image')}}:</label>
                            <input type="text" id="image" name="image" class="form-control" placeholder="Inserisci URL dell'immagine" value="{{ old('image') }}"> 
                        </div>
                        
                        <div class="form-group">
                            <label for="name" class="font-weight-bold">{{__('messages.name')}}:</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required> 
                        </div>

                        <div class="form-group">
                            <label for="description" class="font-weight-bold">{{__('messages.description')}}:</label>
                            <textarea id="description" name="description" class="form-control" rows="3" required>{{ old('description') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="start" class="font-weight-bold">{{__('messages.start')}}:</label>
                            <input type="date" id="start" name="start" class="form-control" value="{{ old('start') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="end" class="font-weight-bold">{{__('messages.end')}}:</label>
                            <input type="date" id="end" name="end" class="form-control" value="{{ old('end') }}" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="font-weight-bold">{{__('messages.related_products')}}</h5>

                        <div id="associated-products">
                            </div>

                        <div id="add-product-section">
                            <h4>{{__('messages.add_product')}}</h4>
                            <select id="new-product-select">
                                <option value="">-- {{__('messages.select_product')}} --</option>
                                @foreach($products as $product) 
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                            <input type="number" id="new-product-discount" step="0.01" min="0" max="1" placeholder="Sconto">
                            <button type="button" id="add-product-btn">{{__('messages.add')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12 text-center">
                <button type="submit" class="btn btn-success">{{__('messages.create_promotion')}}</button>
                <a href="{{ route('admin.promotions.index') }}" class="btn btn-secondary">{{__('messages.dismiss')}}</a>
            </div>
        </div>
    </form>

    <script>
        $(document).ready(function() {
            $('#add-product-btn').on('click', function() {
                var productId = $('#new-product-select').val();
                var productName = $('#new-product-select option:selected').text();
                var discount = $('#new-product-discount').val();

                if (productId && discount) {
                    if ($(`#product-${productId}`).length === 0) {
                        var productHtml = `
                            <div class="product-entry"> <div class="row mb-2">
                                <div class="col-md-6"> 
                                    <input type="text" class="form-control" value="${productName}" readonly>
                                    <input type="hidden" name="product_ids[]" value="${productId}"> 
                                </div>
                                <div class="col-md-4">
                                    <input type="number" name="discounts[${productId}]" id="product-${productId}" value="${discount}" step="0.01" min="0" max="1">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger remove-product-btn" data-product-id="${productId}">{{__('messages.remove')}}</button>
                                </div>
                            </div>
                        `;

                        $('#associated-products').append(productHtml);
                        $('#associated-products').css('display', 'block'); 
                    } else {
                        alert(__('messages.already_added'));
                    }
                } else {
                    alert(__('messages.select_product_and_discount'));
                }
            });

            $(document).on('click', '.remove-product-btn', function() {
                var productId = $(this).data('product-id');
                $(this).closest('.product-entry').remove(); 
            });
        });
    </script>
@endsection
