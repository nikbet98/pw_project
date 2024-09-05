@extends('layout.adminMaster')

@section('active_promotions', 'active')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{__('messages.home_section')}}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.promotions.index') }}">{{__('messages.promotions_section')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('messages.edit_promotion_section')}}</li>
@endsection

@section('body')
    <form action="{{ route('admin.promotion.update', $promotion->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            
            <!-- Sezione Dettagli Promozione -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="image" class="font-weight-bold">{{__('messages.url_image')}}:</label>
                            <input type="text" id="image" name="image" class="form-control"  value="{{ $promotion->image }}"> 
                        </div>

                        <div class="form-group">
                            <label for="name" class="font-weight-bold">{{__('messages.name')}}:</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ $promotion->name }}" required>
                        </div>

                        <div class="form-group">
                            <label for="description" class="font-weight-bold">{{__('messages.description')}}:</label>
                            <textarea id="description" name="description" class="form-control" rows="3" required>{{ $promotion->description }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="start_date" class="font-weight-bold">{{__('messages.start')}}:</label>
                            <input type="date" id="start_date" name="start_date" class="form-control" value="{{ $promotion->start}}" required>
                        </div>

                        <div class="form-group">
                            <label for="end_date" class="font-weight-bold">{{__('messages.end')}}:</label>
                            <input type="date" id="end_date" name="end_date" class="form-control" value="{{ $promotion->end}}" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sezione Prodotti Associati -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="font-weight-bold">{{__('messages.related_products')}}</h5>
                        
                        <!-- Lista Prodotti Associati -->
                        <div id="associated-products">
                            @foreach ($productInPromotion as $product)
                                <div class="product-entry">
                                    <div class="row mb-2">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" value="{{ $product->name }}" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="number" name="discounts[{{ $product->id }}]" id="product-{{ $product->id }}" value="{{ $product->promotion()->withPivot('discount')->where('promotion_id', $promotion->id)->first()->pivot->discount }}" step="0.01" min="0" max="1">
                                        </div>                                        
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger remove-product-btn" data-product-id="{{ $product->id }}">{{__('messages.remove')}}</button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Sezione per Aggiungere Prodotti -->
                        <div id="add-product-section">
                            <h4>{{__('messages.add_product')}}</h4>
                            <select id="new-product-select">
                                <option value="">-- {{__('messages.select_product')}} --</option>
                                @foreach($availableProducts as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                            <input type="number" id="new-product-discount" step="0.01" min="0" max="1" placeholder="{{__('messages.discount')}}">
                            <button type="button" id="add-product-btn">{{__('messages.add')}}</button>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12 text-center">
                <button type="submit" class="btn btn-success">{{__('messages.save_changes')}}</button>
                <a href="{{ route('admin.promotions.index') }}" class="btn btn-secondary">{{__('messages.dismiss')}}</a>
            </div>
        </div>
    </form>

    <!-- Script per Gestire Aggiunta e Rimozione Prodotti -->
    <script>
        $(document).ready(function() {
            // Aggiungi un nuovo prodotto alla lista
            $('#add-product-btn').on('click', function() {
                var productId = $('#new-product-select').val();
                var productName = $('#new-product-select option:selected').text();
                var discount = $('#new-product-discount').val();

                if (productId && discount) {
                    // Controlla se il prodotto è già stato aggiunto
                    if ($(`#product-${productId}`).length === 0) {
                        // Crea un nuovo elemento per il prodotto
                        var productHtml = `
                            <div class="product-entry"> <div class="row mb-2">
                                <div class="col-md-6"> 
                                    <input type="text" class="form-control" value="${productName}" readonly>
                                </div>
                                <div class="col-md-4">
                                    <input type="number" name="discounts[${productId}]" id="product-${productId}" value="${discount}" step="0.01" min="0" max="1">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger remove-product-btn" data-product-id="${productId}">Rimuovi</button>
                                </div>
                            </div>
                        `;

                        $('#associated-products').append(productHtml);
                        $('#associated-products').css('display', 'block');

                        // Force CSS reload
                        $('link[rel="stylesheet"]').each(function() {
                            this.href = this.href.replace(/\?.*|$/, '?' + new Date().getTime());
                        });

                    } else {
                        alert("Questo prodotto è già stato aggiunto.");
                    }
                } else {
                    alert("Seleziona un prodotto e specifica uno sconto.");
                }
            });

            // Rimuovi un prodotto dalla lista
            $(document).on('click', '.remove-product-btn', function() {
                var productId = $(this).data('product-id');
                $(this).closest('.product-entry').remove();
            });
        });

    </script>
@endsection
