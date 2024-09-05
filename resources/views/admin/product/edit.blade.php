@extends('layout.adminMaster')

<!-- Additional styles for this page -->
<link rel="stylesheet" href="{{ asset('css/product.css') }}">
<link rel="stylesheet" href="{{ asset('css/scrollable_list.css') }}">
<link rel="stylesheet" href="{{ asset('css/product_card.css') }}">

@section('active_products','active')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{__('messages.home_section')}}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">{{__('messages.products_section')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('messages.modify')}} {{ $product->name }}</li>
@endsection

@section('body')
    <form action="{{ route('admin.product.update', $product->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-body text-center">
                        <img src="{{ $product->image }}" class="card-img-top mb-3" alt="{{ $product->name }}">
                        <div class="form-group">
                            <label for="image" class="font-weight-bold">{{__('messages.url_image')}}:</label>
                            <input type="text" id="image" name="image" class="form-control" value="{{ $product->image }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name" class="font-weight-bold">{{__('messages.name')}}:</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ $product->name }}">
                        </div>
                        <div class="form-group">
                            <label for="price" class="font-weight-bold">{{__('messages.price')}}:</label>
                            <input type="number" id="price" name="price" class="form-control" value="{{ $product->price }}">
                        </div>
                        <div class="form-group">
                            <label for="brand" class="font-weight-bold">{{__('messages.brand')}}:</label>
                            <input type="text" id="brand" name="brand" class="form-control" value="{{ $product->brand->name }}">
                        </div>
                        <div class="form-group">
                            <label for="category" class="font-weight-bold">{{__('messages.category')}}:</label>
                            <select id="category" name="category" class="form-control">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $product->subcategory->category->id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="subcategory" class="font-weight-bold">{{__('messages.subcategory')}}:</label>
                            <select id="subcategory" name="subcategory" class="form-control">
                                @foreach($subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}" {{ $product->subcategory->id == $subcategory->id ? 'selected' : '' }}>
                                        {{ $subcategory->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="description" class="font-weight-bold">{{__('messages.description')}}:</label>
                            <textarea id="description" name="description" class="form-control" rows="4">{{ $product->description }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <button type="submit" class="btn btn-success">{{__('messages.save_changes')}}</button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">{{__('messages.dismiss')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
