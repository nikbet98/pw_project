@extends('layout.master')
<link rel="stylesheet" href="{{ asset('css/product_card.css') }}">

@section('active_products','active')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('messages.home_section')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('messages.products_section')}}</li>
@endsection

@section('body')
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-header">
                Filtri
            </div>
            <div class="card-body">
                <!-- Form per i filtri -->
                <form action="{{ route('product.filter') }}" method="GET" id="filterForm">
                    <input type="text" id="search" name="search" value="{{ $search }}" style="display: none;">
                    <input type="hidden" name="brand" value="{{ $selectedBrand }}">  <!-- Hidden input for brand -->
                    <input type="hidden" name="category" value="{{ $selectedCategory }}">  <!-- Hidden input for category -->
                    <input type="hidden" name="subcategory" value="{{ $selectedSubcategory }}">  <!-- Hidden input for subcategory -->
                    <div class="form-group">
                        <label for="brand">{{__('messages.brand_dropdown')}}:</label>
                        <select class="form-control" id="brand" name="brand">
                            <option value="">{{__('messages.all_brands')}}</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}" {{ $brand->id == $selectedBrand ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="category">{{__('messages.categories_dropdown')}}:</label>
                        <select class="form-control" id="category" name="category">
                            <option value="">{{__('messages.all_categories')}}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ $category->id == $selectedCategory ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="subcategory">{{__('messages.subcategory_dropdown')}}:</label>
                        <select class="form-control" id="subcategory" name="subcategory">
                            <option value="">{{__('messages.all_subcategories')}}</option>
                            @foreach ($subcategories as $subcategory)
                                <option value="{{ $subcategory->id }}" {{ $subcategory->id == $selectedSubcategory ? 'selected' : '' }}>{{ $subcategory->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" onclick="function(event){
                        event.preventdefault(); 
                        gino();
                   }">{{__('messages.apply_filters')}} </button>
                </form>
                
            </div>
        </div>
    </div>
    
    <div class="col-md-9">
        <div class="row" id="productGrid"> 
            @if ($search)
                <div class="search-title text-center mb-4 mx-auto">
                    <h4>
                        <i class="fas fa-search mr-2"></i> {{__('messages.related_results')}}: "{{ $search }}"
                    </h4>
                </div>
            @endif       
            <div class="row">
                <!-- Griglia dei prodotti -->
                @foreach ($products as $product)
                    <div class="col-md-4 mb-4">
                        @component('components.product_card',[
                            'product' => $product,
                        ])  
                        @endcomponent
                    </div>
                @endforeach
            </div>
            <div class="text-center mb-4 mx-auto">
                {{ $products->appends(request()->except('page'))->links() }}
            </div>   
        </div>
    </div>
    
    
</div>
@endsection
